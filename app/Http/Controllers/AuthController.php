<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Data wajib diisi!',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Data wajib diisi!',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->redirectByRole($user)->with('success', 'Login Berhasil');
        }

        return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'namaLengkap' => 'required|max:300',
            'noTelp' => 'required|regex:/^[0-9]+$/|unique:users,noTelp',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'required' => 'Data harus diisi!',
            'email.unique' => 'Email sudah terdaftar.',
            'noTelp.unique' => 'Nomor telepon sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $otp = rand(100000, 999999);

        session([
            'register_data' => [
                'namaLengkap' => $validated['namaLengkap'],
                'email' => $validated['email'],
                'noTelp' => $validated['noTelp'],
                'password' => Hash::make($validated['password']),
                'isAdmin' => false
            ],
            'register_otp' => $otp,
            'register_otp_expires' => now()->addMinutes(10)
        ]);

        Mail::send('emails.otp', ['otp' => $otp, 'namaLengkap' => $validated['namaLengkap']], function ($message) use ($validated) {
            $message->to($validated['email'])->subject('Kode OTP Verifikasi AGRIS');
        });

        return redirect()->route('otp.form')->with('success', 'Kode OTP telah dikirim ke email');
    }

    public function showOtpForm()
    {
        if (!session('register_data')) {
            return redirect()->route('register');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if (!session('register_data')) {
            return redirect()->route('register');
        }

        if (now()->gt(session('register_otp_expires'))) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa');
        }

        if ($request->otp != session('register_otp')) {
            return back()->with('error', 'Kode OTP salah');
        }

        $user = User::create(session('register_data'));
        session()->forget(['register_data', 'register_otp', 'register_otp_expires']);

        Auth::login($user);

        return $this->redirectByRole($user)->with('success', 'Akun berhasil dibuat.');
    }

    public function resendOtp(Request $request)
    {
        if (!session('register_data')) {
            return redirect()->route('register');
        }

        $otp = rand(100000, 999999);
        session([
            'register_otp' => $otp,
            'register_otp_expires' => now()->addMinutes(10)
        ]);

        $email = session('register_data')['email'];

        Mail::send('emails.otp', ['otp' => $otp, 'namaLengkap' => session('register_data')['namaLengkap']], function ($message) use ($email) {
            $message->to($email)->subject('Resend: Kode OTP Verifikasi AGRIS');
        });

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }

        try {
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'namaLengkap' => $googleUser->getName(),
                    'email'       => $googleUser->getEmail(),
                    'password'    => Hash::make(Str::random(24)),
                    'noTelp'      => 'g' . Str::random(11),
                    'isAdmin'     => false,
                    'isActive'    => true,
                ]);
            }

            Auth::login($user, true);
            return $this->redirectByRole($user)->with('success', 'Login Berhasil');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login Google: ' . $e->getMessage());
        }
    }

    private function redirectByRole($user)
    {
        if ($user->isAdmin) {
            return redirect()->intended(route('admin.produk.index'));
        }
        return redirect()->intended(route('agen.produk.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset telah dikirim.')
            : back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    public function resetForm($token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request()->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectByRole($request->user());
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi baru telah dikirim.');
    }
}
