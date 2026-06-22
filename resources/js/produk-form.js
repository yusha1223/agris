const selectJenis = document.getElementById('selectJenis');
const selectMutu = document.getElementById('selectMutu');
const selectKarung = document.getElementById('selectKarung');

if (selectJenis) {
    selectJenis.addEventListener('change', function() {
        const val = this.value;
        resetDropdown(selectMutu, '-- Pilih Mutu --');
        resetDropdown(selectKarung, '-- Pilih Berat --');

        if (val) {
            const listMutu = [...new Set(dataKategori
                .filter(k => k.jenisKategori === val)
                .map(k => k.mutu))];
            populateDropdown(selectMutu, listMutu);
        }
    });
}

if (selectMutu) {
    selectMutu.addEventListener('change', function() {
        const valJenis = selectJenis.value;
        const valMutu = this.value;
        resetDropdown(selectKarung, '-- Pilih Berat --');

        if (valMutu) {
            const listKarung = [...new Set(dataKategori
                .filter(k => k.jenisKategori === valJenis && k.mutu === valMutu)
                .map(k => k.karung))];
            populateDropdown(selectKarung, listKarung);
        }
    });
}

function populateDropdown(target, data) {
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item;
        opt.textContent = item.toString().toUpperCase();
        target.appendChild(opt);
    });
    target.disabled = false;
    target.classList.replace('bg-gray-50', 'bg-white');
}

function resetDropdown(target, placeholder) {
    target.innerHTML = `<option value="">${placeholder}</option>`;
    target.disabled = true;
    target.classList.replace('bg-white', 'bg-gray-50');
}

function formatRupiah(el) {
    let val = el.value.replace(/[^0-9]/g, '');
    document.getElementById('hargaAsli').value = val;
    el.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
}

function previewImage(input) {
    const preview = document.getElementById('previewImg');
    const placeholder = document.getElementById('placeholderIcon');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function openConfirmModal() {
    document.getElementById('modalKonfirmasiProduk').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('modalKonfirmasiProduk').classList.add('hidden');
}

const btnSubmit = document.getElementById('btnSubmitForm');
if (btnSubmit) {
    btnSubmit.addEventListener('click', () => {
        document.getElementById('formProduk').submit();
    });
}

const btnClose = document.getElementById('btnCloseModal');
if (btnClose) {
    btnClose.addEventListener('click', closeConfirmModal);
}
