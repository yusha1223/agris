@extends('layouts.admin')
@section('title', 'Chat Admin - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-2 pb-4 h-[calc(100vh-70px)] min-h-500px">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row overflow-hidden h-full" id="chat-app" v-cloak>
        <div :class="activeTarget ? 'hidden md:flex' : 'flex'" class="w-full md:w-80 flex-col bg-white border-r border-gray-100 shrink-0 h-full">
            <div class="px-6 py-4 flex flex-col border-b border-slate-100 shrink-0 bg-white gap-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col">
                            <h1 class="text-lg font-black text-green-700 leading-none">AGRIS</h1>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Panel Chat Admin</span>
                        </div>
                    </div>
                    <button @click="openGlobalChat" :class="activeTarget === 'GLOBAL' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700'" class="px-3 py-2 rounded-xl text-[10px] font-bold transition-all hover:scale-105 active:scale-95 shadow-sm">
                        BROADCAST
                    </button>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" v-model="searchQuery" placeholder="Cari agen..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-green-500 transition-all">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div v-for="u in filteredUsers" :key="u.id"
                     @click="loadChat(u.id, u.namaLengkap, u.fotoProfil)"
                     :class="activeTarget == u.id ? 'bg-green-50 border-r-4 border-green-600' : 'hover:bg-slate-50 border-r-4 border-transparent'"
                     class="p-4 border-b border-slate-50 cursor-pointer transition-all flex items-center gap-4">
                    <div class="relative shrink-0">
                        <img :src="u.fotoProfil" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-white">
                        <div v-if="unreadUsers.includes(u.id)" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-slate-800 text-sm truncate">@{{ u.namaLengkap }}</p>
                        <div class="flex items-center gap-1 mt-0.5">
                            <span :class="u.isActive ? 'text-green-600' : 'text-yellow-400'" class="text-[10px] font-bold">
                                @{{ u.isActive ? 'Mitra Aktif' : 'Mitra Non-Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div :class="activeTarget ? 'flex' : 'hidden md:flex'" class="flex-1 flex-col min-w-0 bg-[#f8fafc] h-full relative overflow-hidden">
            <template v-if="activeTarget">
                <div class="h-18 px-6 flex items-center justify-between border-b border-slate-200 shrink-0 bg-white/80 backdrop-blur-md z-20 shadow-sm">
                    <div class="flex items-center gap-4">
                        <button @click="activeTarget = null" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <img :src="activeTargetPhoto" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200">
                        <div class="min-w-0">
                            <h2 class="font-bold text-slate-800 text-sm uppercase truncate">@{{ activeTargetName }}</h2>
                        </div>
                    </div>
                    <button @click="activeTarget = null" class="hidden md:flex w-10 h-10 items-center justify-center rounded-full text-slate-300 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar bg-[#f8fafc]" id="chat-container">
                    <div v-for="(group, date) in groupedChats" :key="date">
                        <div class="flex justify-center my-6">
                            <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">@{{ date }}</span>
                        </div>

                        <div v-for="chat in group" :key="chat.id" :class="chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start'" class="mb-4">
                            <div class="max-w-[85%] md:max-w-[70%] group flex items-start gap-1">
                                <div v-if="chat.id_pengirim == @js(Auth::id())" class="flex items-center self-center gap-1 order-1">
                                    <div v-if="activeMenu === chat.id">
                                        <button @click.prevent="deleteChat(chat.id)" class="bg-white px-3 py-1.5 rounded-lg shadow-md border border-slate-100 text-[10px] font-black text-red-600 hover:bg-red-50 whitespace-nowrap">HAPUS</button>
                                    </div>
                                    <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-1.5 text-slate-400 hover:text-green-600 transition-all">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                </div>

                                <div :class="chat.id_penerima == 'GLOBAL' ? 'bg-amber-400 text-amber-950 order-2 rounded-2xl' : (chat.id_pengirim == @js(Auth::id()) ? 'bg-green-600 text-white rounded-tr-none order-2' : 'bg-white text-slate-700 border-none rounded-tl-none order-2')" class="px-4 py-3 rounded-3xl shadow-sm">
                                    <div v-if="chat.foto_chat" class="mb-2 rounded-xl overflow-hidden">
                                        <img :src="chat.foto_chat_url || chat.foto_chat" class="w-full max-h-96 object-cover">
                                    </div>
                                    <p class="text-sm font-medium">@{{ chat.pesan }}</p>
                                    <div class="flex justify-end items-center gap-1.5 mt-1 text-[9px] font-bold opacity-80">
                                        <span>@{{ formatTime(chat.waktu_chat) }}</span>
                                        <template v-if="chat.id_pengirim == @js(Auth::id()) && chat.id_penerima !== 'GLOBAL'">
                                            <i v-if="chat.status === 'dibaca'" class="fa-solid fa-check-double text-blue-200"></i>
                                            <i v-else class="fa-solid fa-check text-green-200"></i>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white border-t border-slate-200">
                    <div v-if="imagePreview" class="mb-4 p-3 bg-green-50 rounded-2xl flex justify-between items-center">
                        <span class="text-xs font-bold text-green-800">@{{ selectedFile?.name }}</span>
                        <button @click="cancelImage" class="text-red-500"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-100 p-2 rounded-4xl">
                        <label class="w-10 h-10 flex items-center justify-center text-slate-400 cursor-pointer hover:text-green-600">
                            <i class="fa-solid fa-image"></i>
                            <input type="file" @change="handleFileUpload" class="hidden" id="file-input-field">
                        </label>
                        <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 focus:outline-none text-sm">
                        <button @click="sendChat" class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0 active:scale-95 transition-transform"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </div>
            </template>

            <div v-else class="flex-1 flex items-center justify-center p-8 md:p-16 bg-white">
                <div class="w-full h-full border-4 border-dashed border-slate-50 rounded-[50px] flex flex-col items-center justify-center text-center p-8">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-comments text-4xl text-slate-200"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-300 uppercase tracking-[0.4em]">Pusat Pesan</h3>
                    <p class="text-slate-400 text-xs mt-2 font-bold uppercase tracking-widest">Pilih agen untuk memulai percakapan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick, computed } = Vue;
    createApp({
        setup() {
            const users = ref(@js($users).map(u => {
                return {
                    id: u.id,
                    namaLengkap: u.namaLengkap || 'Agen',
                    isActive: u.isActive,
                    fotoProfil: u.fotoProfilUrl || `https://ui-avatars.com/api/?name=${encodeURIComponent(u.namaLengkap || 'U')}&background=dcfce7&color=15803d`
                };
            }));
            const chats = ref([]);
            const activeTarget = ref(null);
            const activeTargetName = ref('');
            const activeTargetPhoto = ref('');
            const newMessage = ref('');
            const selectedFile = ref(null);
            const imagePreview = ref(false);
            const activeMenu = ref(null);
            const searchQuery = ref('');
            const unreadUsers = ref([]);

            const parseDate = (t) => {
                if (!t) return new Date();
                if (t instanceof Date) return t;
                if (typeof t === 'object' && t.date) {
                    t = t.date;
                }
                if (typeof t !== 'string') return new Date();
                let s = t.trim().replace(' ', 'T');
                let d = new Date(s);
                if (isNaN(d.getTime())) {
                    d = new Date(t.replace(/-/g, '/'));
                }
                return isNaN(d.getTime()) ? new Date() : d;
            };

            const groupedChats = computed(() => {
                const groups = {};
                chats.value.forEach(chat => {
                    const d = parseDate(chat.waktu_chat);
                    const date = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    if (!groups[date]) groups[date] = [];
                    groups[date].push(chat);
                });
                return groups;
            });

            const filteredUsers = computed(() => {
                return users.value.filter(u => u.namaLengkap.toLowerCase().includes(searchQuery.value.toLowerCase()));
            });

            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            const loadChat = (id, name, photo) => {
                activeTarget.value = id;
                activeTargetName.value = name;
                activeTargetPhoto.value = photo;
                unreadUsers.value = unreadUsers.value.filter(uid => uid !== id);
                axios.get(`/chat/${id}`).then(res => {
                    chats.value = res.data.chats;
                    scrollToBottom();
                });
            };

            const openGlobalChat = () => loadChat('GLOBAL', 'Pusat Informasi (Broadcast)', 'https://ui-avatars.com/api/?name=B&background=fbbf24&color=fff');

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', activeTarget.value);
                formData.append('pesan', newMessage.value || '');
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);
                newMessage.value = '';
                cancelImage();
                axios.post('/chat', formData).then(res => {
                    if (!chats.value.some(c => c.id === res.data.id)) {
                        chats.value.push(res.data);
                        scrollToBottom();
                    }
                });
            };

            const handleFileUpload = async (e) => {
                const file = e.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        if (typeof window.compressImageDirectly === 'function') {
                            selectedFile.value = await window.compressImageDirectly(file, 1200, 1200, 0.7);
                        } else {
                            selectedFile.value = file;
                        }
                    } else {
                        selectedFile.value = file;
                    }
                    imagePreview.value = true;
                }
            };

            const cancelImage = () => {
                selectedFile.value = null; imagePreview.value = false;
                const field = document.getElementById('file-input-field');
                if(field) field.value = '';
            };

            const deleteChat = (id) => {
                axios.delete(`/chat/${id}`).then(() => {
                    chats.value = chats.value.filter(c => c.id !== id);
                    activeMenu.value = null;
                });
            };

            const formatTime = (t) => {
                if (!t) return 'Baru saja';
                const d = parseDate(t);
                return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            };

            const toggleMenu = (id) => activeMenu.value = activeMenu.value === id ? null : id;

            onMounted(() => {
                window.addEventListener('click', () => activeMenu.value = null);
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${@js(Auth::id())}`).listen('.MessageSent', (e) => {
                            if (e.is_delete) {
                                chats.value = chats.value.filter(c => c.id !== e.chat.id);
                            } else if (e.is_read_update) {
                                chats.value.forEach(c => {
                                    if (c.id_pengirim == @js(Auth::id()) && c.id_penerima == e.chat.id_pengirim) c.status = 'dibaca';
                                });
                            } else {
                                if (activeTarget.value == e.chat.id_pengirim) {
                                    if (!chats.value.some(c => c.id === e.chat.id)) {
                                        chats.value.push(e.chat);
                                        scrollToBottom();
                                        axios.get(`/chat/${e.chat.id_pengirim}`);
                                    }
                                } else {
                                    if (!unreadUsers.value.includes(e.chat.id_pengirim)) unreadUsers.value.push(e.chat.id_pengirim);
                                }
                            }
                        });
                        window.Echo.channel('chat.global').listen('.MessageSent', (e) => {
                            if (activeTarget.value === 'GLOBAL' && !chats.value.some(c => c.id === e.chat.id)) {
                                chats.value.push(e.chat);
                                scrollToBottom();
                            }
                        });
                    }
                }, 500);
            });

            return { users, filteredUsers, unreadUsers, searchQuery, chats, groupedChats, activeTarget, activeTargetName, activeTargetPhoto, newMessage, imagePreview, selectedFile, activeMenu, loadChat, openGlobalChat, sendChat, handleFileUpload, cancelImage, deleteChat, formatTime, toggleMenu };
        }
    }).mount('#chat-app');
</script>
@endsection
