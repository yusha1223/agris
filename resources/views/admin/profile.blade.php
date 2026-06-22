@extends('layouts.admin')

@section('title', 'Profil Admin - AGRIS')

@section('content')
<div class="max-w-full mx-auto py-4">
    <div class="flex flex-col lg:flex-row gap-3 items-start">
        <div class="w-full lg:w-auto" data-aos="fade-up">
            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border text-white bg-[#58CC02] rounded-xl transition-all duration-300 text-xs font-bold group">
                <i class="fas fa-arrow-left"></i> Beranda
            </a>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile" class="flex-1 w-full">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h1 class="text-xl font-black text-gray-800">Profil</h1>
                    <div class="flex gap-2">
                        <button type="button" id="editBtn" class="px-5 py-2 bg-[#58CC02] text-white text-xs font-bold rounded-lg transition">Edit</button>
                        <button type="button" id="cancelBtn" class="hidden px-5 py-2 bg-red-500 text-white text-xs font-bold rounded-lg transition">Batal</button>
                        <button type="button" id="confirmBtn" class="hidden px-5 py-2 bg-[#58CC02] text-white text-xs font-bold rounded-lg transition shadow-md shadow-green-100">Simpan</button>
                    </div>
                </div>

                <div class="p-6 flex flex-col lg:flex-row gap-8">
                    <div class="w-full lg:w-1/4 flex flex-col items-center">
                        <div class="group relative w-40 h-40 rounded-full overflow-hidden shadow-md bg-gray-50 border-2 border-white">
                            <img id="previewFoto" src="{{ $user->fotoProfil ? storage_url($user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode($user->email ?? 'User') }}" class="w-full h-full object-cover">
                            <label for="fotoProfil" id="overlayFoto" class="hidden absolute inset-0 bg-black/40 items-center justify-center cursor-pointer transition">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </label>
                        </div>
                        <input type="file" name="fotoProfil" id="fotoProfil" class="hidden" accept="image/*">
                        <label for="fotoProfil" id="btnPilihFoto" class="hidden mt-3 px-4 py-1.5 border bg-[#58CC02] text-white rounded-lg text-xs font-bold cursor-pointer transition">Edit Foto</label>
                        <p id="infoFoto" class="hidden text-center pt-2 text-[10px] text-blue-500 leading-tight">
                            Maks 10mb (.PNG, .JPG, .JPEG)
                        </p>
                    </div>

                    <div class="w-full lg:w-3/4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Email</label>
                                <input type="text" name="email" value="{{ old('email', $user->email) }}" class="form-input editable w-full rounded-xl border-gray-200 bg-gray-50/50 py-2 px-3 text-sm disabled:text-black transition-all font-medium" disabled>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Nomor Telepon</label>
                                <input type="text" name="noTelp" value="{{ old('noTelp', $user->noTelp) }}" class="form-input editable w-full rounded-xl border-gray-200 bg-gray-50/50 py-2 px-3 text-sm disabled:text-black transition-all font-medium" disabled>
                                @error('noTelp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Provinsi</label>
                                    <div id="provinsiView" class="py-2 px-3 text-sm rounded-xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '-' }}
                                    </div>
                                    <select id="provinsi" name="provinsiId" data-old="{{ $user->desa->kecamatan->kabupaten->provinsi->id ?? '' }}" class="hidden form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm focus:ring-0">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Kabupaten</label>
                                    <div id="kabupatenView" class="py-2 px-3 text-sm rounded-xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? '-' }}
                                    </div>
                                    <select id="kabupaten" name="kabupatenId" data-old="{{ $user->desa->kecamatan->kabupaten->id ?? '' }}" class="hidden form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm focus:ring-0">
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Kecamatan</label>
                                    <div id="kecamatanView" class="py-2 px-3 text-sm rounded-xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->namaKecamatan ?? '-' }}
                                    </div>
                                    <select id="kecamatan" name="kecamatanId" data-old="{{ $user->desa->kecamatan->id ?? '' }}" class="hidden form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm focus:ring-0">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Desa</label>
                                    <div id="desaView" class="py-2 px-3 text-sm rounded-xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->namaDesa ?? '-' }}
                                    </div>
                                    <select id="desa" name="desaId" data-old="{{ $user->desaId ?? '' }}" class="hidden form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm focus:ring-0">
                                        <option value="{{ $user->desaId ?? '' }}">{{ $user->desa->namaDesa ?? 'Pilih Desa' }}</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Detail Alamat</label>
                                <textarea name="detailAlamat" class="form-input editable w-full rounded-xl border-gray-200 bg-gray-50/50 py-2 px-3 text-sm disabled:text-black transition-all font-medium resize-none" rows="2" disabled>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
                            </div>

                            <div id="passwordSection" class="hidden mt-4 pt-4 border-t border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Password Lama</label>
                                        <input type="password" name="current_password" id="current_password" class="form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm pr-10 transition-all font-medium" placeholder="Masukkan password lama">
                                        <button type="button" class="toggle-password absolute right-3 top-8 text-gray-400 hover:text-[#58CC02]">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="relative">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Password Baru</label>
                                        <input type="password" name="password" id="password" class="form-input editable w-full rounded-xl border-[#58CC02] bg-white py-2 px-3 text-sm pr-10 transition-all font-medium" placeholder="Masukkan password baru">
                                        <button type="button" class="toggle-password absolute right-3 top-8 text-gray-400 hover:text-[#58CC02]">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 mt-2 italic">*Kosongkan bagian password jika tidak ingin mengubahnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<x-modal id="confirmModal" message="Apakah yakin melakukan perubahan profil?" confirmText="Iya" cancelText="Batal" confirmId="btnSubmitProfile" cancelId="btnCloseProfileModal" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const form = document.getElementById('formProfile');
        const editableInputs = document.querySelectorAll('.editable');
        const passwordSection = document.getElementById('passwordSection');

        async function activateEditMode() {
            editableInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-gray-50/50', 'border-gray-200');
                input.classList.add('bg-white', 'border-[#58CC02]');
            });

            ['provinsi', 'kabupaten', 'kecamatan', 'desa'].forEach(id => {
                const viewEl = document.getElementById(id + 'View');
                const selectEl = document.getElementById(id);
                if (viewEl) viewEl.classList.add('hidden');
                if (selectEl) selectEl.classList.remove('hidden');
            });

            editBtn?.classList.add('hidden');
            cancelBtn?.classList.remove('hidden');
            confirmBtn?.classList.remove('hidden');
            passwordSection?.classList.remove('hidden');

            document.getElementById('infoFoto')?.classList.remove('hidden');
            document.getElementById('btnPilihFoto')?.classList.remove('hidden');
            document.getElementById('overlayFoto')?.classList.replace('hidden', 'flex');

            if (typeof window.initWilayah === 'function') {
                await window.initWilayah();
            }
        }

        @if($errors->any())
            activateEditMode();
        @endif

        if(editBtn) {
            editBtn.addEventListener('click', (e) => {
                e.preventDefault();
                activateEditMode();
            });
        }

        if(cancelBtn) {
            cancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.reload();
            });
        }

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        if(confirmBtn) {
            confirmBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof openModal === 'function') {
                    openModal('confirmModal');
                } else {
                    document.getElementById('confirmModal').classList.remove('hidden');
                }
            });
        }

        document.getElementById('btnSubmitProfile')?.addEventListener('click', (e) => {
            e.preventDefault();
            form.submit();
        });

        document.getElementById('btnCloseProfileModal')?.addEventListener('click', (e) => {
            e.preventDefault();
            if (typeof closeModal === 'function') {
                closeModal('confirmModal');
            } else {
                document.getElementById('confirmModal').classList.add('hidden');
            }
        });

        document.getElementById('fotoProfil').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => document.getElementById('previewFoto').src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection
