<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class c_chat extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $users = User::isAdmin(false)->get();

            $unreadUserIds = Chat::where('id_penerima', $user->id)
                ->where('status', 'terkirim')
                ->distinct()
                ->pluck('id_pengirim')
                ->toArray();

            return view('admin.chat.index', compact('users', 'unreadUserIds'));
        }

        $admin = User::isAdmin(true)->first();
        $chats = Chat::query()->where(function($q) use ($user, $admin) {
            $q->where(function($inner) use ($user, $admin) {
                $inner->where('id_pengirim', $user->id)->where('id_penerima', $admin->id);
            })->orWhere(function($inner) use ($user, $admin) {
                $inner->where('id_pengirim', $admin->id)->where('id_penerima', $user->id);
            });
        })->orWhere('id_penerima', 'GLOBAL')->orderBy('waktu_chat', 'asc')->get();

        Chat::whereIdPengirim($admin->id)->whereIdPenerima($user->id)->whereStatus('terkirim')->update(['status' => 'dibaca']);

        $lastChat = $chats->where('id_pengirim', $admin->id)->last();
        if($lastChat) {
            broadcast(new MessageSent(['id_penerima' => $admin->id, 'id_pengirim' => $user->id, 'status' => 'dibaca'], false, true))->toOthers();
        }

        return view('agen.chat.index', compact('chats', 'admin'));
    }

    public function show(string $id)
    {
        $user = Auth::user();
        if ($id === 'GLOBAL') {
            $chats = Chat::whereIdPenerima('GLOBAL')->orderBy('waktu_chat', 'asc')->get();
            return response()->json(['target' => ['id' => 'GLOBAL', 'name' => 'PENGUMUMAN GLOBAL'], 'chats' => $chats]);
        }

        $targetUser = User::findOrFail($id);
        $chats = Chat::query()->where(function($q) use ($user, $targetUser) {
            $q->where(function($inner) use ($user, $targetUser) {
                $inner->where('id_pengirim', $user->id)->where('id_penerima', $targetUser->id);
            })->orWhere(function($inner) use ($user, $targetUser) {
                $inner->where('id_pengirim', $targetUser->id)->where('id_penerima', $user->id);
            });
        })->orderBy('waktu_chat', 'asc')->get();

        Chat::whereIdPengirim($targetUser->id)->whereIdPenerima($user->id)->whereStatus('terkirim')->update(['status' => 'dibaca']);

        broadcast(new MessageSent(['id_penerima' => $targetUser->id, 'id_pengirim' => $user->id, 'status' => 'dibaca'], false, true))->toOthers();

        return response()->json(['target' => $targetUser, 'chats' => $chats]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penerima' => 'required',
            'pesan' => 'required_without:foto_chat',
            'foto_chat' => 'nullable|image|max:5120'
        ]);

        $pesan = trim($request->pesan);
        if (empty($pesan) && !$request->hasFile('foto_chat')) {
            return response()->json(['error' => 'Pesan tidak boleh kosong'], 422);
        }

        try {
            $path = null;
            if ($request->hasFile('foto_chat')) {
                $path = $request->file('foto_chat')->store('chats', 'public');
            }

            $chat = Chat::create([
                'id_pengirim' => Auth::id(),
                'id_penerima' => $request->id_penerima,
                'pesan' => $pesan,
                'foto_chat' => $path,
                'status' => 'terkirim',
                'waktu_chat' => now()
            ]);

            $broadcastData = $chat->toArray();
            $broadcastData['foto_chat'] = $chat->foto_chat ? storage_url($chat->foto_chat) : null;

            broadcast(new MessageSent($broadcastData, false))->toOthers();

            return response()->json($broadcastData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            /** @var Chat $chat */
            $chat = Chat::whereId($id)->whereIdPengirim(Auth::id())->firstOrFail();
            broadcast(new MessageSent(['id' => $chat->id, 'id_penerima' => $chat->id_penerima, 'id_pengirim' => $chat->id_pengirim], true))->toOthers();
            if ($chat->foto_chat) {
                $cleanOldPath = ltrim(str_replace('storage/', '', $chat->foto_chat), '/');
                if (Storage::disk('local_public')->exists($cleanOldPath)) {
                    Storage::disk('local_public')->delete($cleanOldPath);
                }
                if (Storage::disk('public')->exists($cleanOldPath)) {
                    Storage::disk('public')->delete($cleanOldPath);
                }
            }
            Chat::whereId($chat->id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
