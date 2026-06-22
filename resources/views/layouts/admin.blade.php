<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script>
        window.currentUserId = "{{ Auth::id() }}";
        window.isAdmin = true;
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

<div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-110">
    <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
</div>

<div class="fixed bottom-5 right-5 z-999999 space-y-3">
    @if(session('success'))
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-green-800">Pesan</div>
                <div class="text-xs text-green-700 mt-0.5">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-red-200 bg-red-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-red-800">Gagal</div>
                <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
            </div>
        </div>
    @endif
</div>

<div class="{{ !Route::is('admin.profile') ? 'md:ml-64' : '' }} transition-all duration-300">
    @include('components.topbar-admin')

    <main class="pt-17 px-3 md:px-6 min-h-screen">
        @yield('content')
    </main>
</div>

@stack('modals')

<!-- Global Cropper Modal -->
<div id="cropperGlobalModal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 bg-black/75 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 max-w-md w-full shadow-2xl flex flex-col items-center">
        <h3 class="text-xl font-black text-gray-800 mb-4">Sesuaikan Gambar</h3>
        <div class="w-full max-h-[300px] overflow-hidden rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 mb-6">
            <img id="cropperGlobalImage" src="" class="max-w-full max-h-[300px] object-contain">
        </div>
        <div class="flex gap-3 w-full">
            <button type="button" id="cropperGlobalCancelBtn" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all active:scale-95 text-xs">
                Batal
            </button>
            <button type="button" id="cropperGlobalSaveBtn" class="flex-1 py-3 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-xl transition-all shadow-lg shadow-green-100 active:scale-95 text-xs">
                Potong & Simpan
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        const progressContainer = document.getElementById('progressBarContainer');
        const progressBar = document.getElementById('progressBar');

        // forms progress bar
        forms.forEach(form => {
            if (form.id !== 'logoutFormReal') {
                form.addEventListener('submit', function () {
                    progressContainer.classList.remove('hidden');
                    let width = 0;
                    const interval = setInterval(() => {
                        if (width >= 90) {
                            clearInterval(interval);
                        } else {
                            width += 10;
                            progressBar.style.width = width + "%";
                        }
                    }, 100);
                });
            }
        });

        const alerts = document.querySelectorAll('.alert-info');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            alert.style.transition = "all 0.5s ease";

            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                setTimeout(() => { alert.remove(); }, 500);
            }, 4000);
        });
    });

    // Image helper functions
    window.compressImageDirectly = function (file, maxWidth, maxHeight, quality) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxWidth) {
                            height = Math.round((height * maxWidth) / width);
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width = Math.round((width * maxHeight) / height);
                            height = maxHeight;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(
                        (blob) => {
                            if (blob) {
                                const compressedFile = new File([blob], file.name.substring(0, file.name.lastIndexOf('.')) + '.jpg', {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                resolve(compressedFile);
                            } else {
                                resolve(file);
                            }
                        },
                        'image/jpeg',
                        quality
                    );
                };
                img.onerror = () => resolve(file);
            };
            reader.onerror = () => resolve(file);
        });
    };

    window.initImageCropper = function({ inputSelector, previewSelector, aspectRatio = NaN, onCropped = null }) {
        const fileInput = document.querySelector(inputSelector);
        if (!fileInput) return;

        fileInput.addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;
            const file = this.files[0];
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const modal = document.getElementById('cropperGlobalModal');
                const img = document.getElementById('cropperGlobalImage');
                if (!modal || !img) return;

                img.src = event.target.result;
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                if (window.globalCropper) {
                    window.globalCropper.destroy();
                }

                window.globalCropper = new Cropper(img, {
                    aspectRatio: aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    restore: false,
                    checkCrossOrigin: false,
                    checkOrientation: false
                });

                const saveBtn = document.getElementById('cropperGlobalSaveBtn');
                const cancelBtn = document.getElementById('cropperGlobalCancelBtn');

                const newSaveBtn = saveBtn.cloneNode(true);
                saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);
                const newCancelBtn = cancelBtn.cloneNode(true);
                cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

                newCancelBtn.addEventListener('click', () => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    fileInput.value = '';
                    if (window.globalCropper) window.globalCropper.destroy();
                });

                newSaveBtn.addEventListener('click', () => {
                    newSaveBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses...';
                    newSaveBtn.disabled = true;

                    const canvas = window.globalCropper.getCroppedCanvas();
                    canvas.toBlob((blob) => {
                        if (blob) {
                            const croppedFile = new File([blob], file.name.substring(0, file.name.lastIndexOf('.')) + '.jpg', {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(croppedFile);
                            fileInput.files = dataTransfer.files;

                            if (previewSelector) {
                                const previewImg = document.querySelector(previewSelector);
                                if (previewImg) {
                                    previewImg.src = URL.createObjectURL(croppedFile);
                                    previewImg.classList.remove('hidden');
                                }
                            }

                            if (onCropped) {
                                onCropped(croppedFile);
                            }
                        }
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                        newSaveBtn.innerHTML = 'Potong & Simpan';
                        newSaveBtn.disabled = false;
                        if (window.globalCropper) window.globalCropper.destroy();
                    }, 'image/jpeg', 0.7);
                });
            };
            reader.readAsDataURL(file);
        });
    };
</script>
</body>
</html>
