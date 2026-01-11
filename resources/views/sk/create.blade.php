@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    @include('component.sidebar')

    <main class="flex-1 p-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            {{-- Header Section --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Buat Surat Keputusan</h1>
                    <p class="text-gray-500 mt-1">Lengkapi formulir di bawah ini untuk menerbitkan SK atau Sertifikat baru.</p>
                </div>
                <div class="hidden md:block">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Draft Baru
                    </span>
                </div>
            </div>

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada isian form:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form id="createSkForm" action="{{ route('sk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    {{-- Section 1: Detail Surat --}}
                    <div class="p-6 md:p-8 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                            Detail Surat
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nomor Agenda & Tanggal --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Agenda (Otomatis)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa-solid fa-hashtag text-gray-400"></i>
                                        </div>
                                        <input type="text" id="nomorAgendaInput"
                                            class="pl-10 block w-full bg-gray-50 border-gray-300 rounded-lg text-gray-500 shadow-sm sm:text-sm focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ $nextNomor ?? '001' }}" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa-regular fa-calendar text-gray-400"></i>
                                        </div>
                                        <input type="date" name="tanggal_ditetapkan"
                                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            value="{{ old('tanggal_ditetapkan', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Jenis & Klasifikasi --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa-solid fa-layer-group text-gray-400"></i>
                                        </div>
                                    <select name="jenis_surat" id="jenisSuratSelect"
                                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required>
                                            <option value="">-- Pilih Jenis Surat --</option>
                                            @foreach($kategoriSK as $kat)
                                            <option value="{{ $kat->jenis_surat }}" data-kode="{{ $kat->kode_klasifikasi }}"
                                                {{ old('jenis_surat') == $kat->jenis_surat ? 'selected' : '' }}>
                                                {{ $kat->jenis_surat }}
                                            </option>
                                            @endforeach
                                            <option value="new_type_input" class="font-bold text-blue-600 bg-blue-50">+ Input Jenis Surat Baru</option>
                                        </select>
                                    </div>
                                    
                                    {{-- Field Input Jenis Surat Baru (Hidden by default) --}}
                                    <div id="newJenisSuratField" class="mt-3 hidden animate-fade-in-down">
                                        <label class="block text-sm font-medium text-blue-700 mb-1">Nama Jenis Surat Baru</label>
                                        <input type="text" name="jenis_surat_baru" id="jenisSuratBaruInput"
                                            class="block w-full border-blue-300 bg-blue-50 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Masukkan nama jenis surat baru..." value="{{ old('jenis_surat_baru') }}">
                                        <p class="text-xs text-blue-600 mt-1">Jenis surat ini akan otomatis disimpan ke sistem.</p>
                                    </div>
                                </div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Klasifikasi <span class="text-red-500">*</span></label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <div class="relative flex-grow focus-within:z-10">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-code text-gray-400"></i>
                                            </div>
                                            <input type="text" name="kode_klasifikasi" id="kodeKlasifikasiInput"
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full rounded-none rounded-l-xl pl-12 py-3 sm:text-base border-gray-300 transition-all font-medium"
                                                value="{{ old('kode_klasifikasi') }}" placeholder="Contoh: DL.01" required>
                                        </div>
                                        <button type="button" onclick="openKlasifikasiModal()"
                                            class="-ml-px relative inline-flex items-center space-x-2 px-6 py-3 border border-gray-300 text-sm font-medium rounded-r-xl text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all hover:shadow-inner">
                                            <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                                            <span class="hidden md:inline font-semibold text-gray-600">Cari</span>
                                        </button>
                                    </div>
                                    
                                    {{-- Selected Klasifikasi Display --}}
                                    <div id="selectedKlasifikasiInfo" class="hidden mt-2 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-3 animate-fade-in-down">
                                        <div class="mt-0.5 text-blue-600">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-blue-800 uppercase tracking-wide">Klasifikasi Terpilih</p>
                                            <p class="text-sm font-medium text-gray-700" id="selectedNamaDisplay">-</p>
                                            <p class="text-xs text-gray-500" id="selectedUraianDisplay"></p>
                                        </div>
                                        <button type="button" id="clearKlasifikasiBtn" class="ml-auto text-gray-400 hover:text-red-500">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>

                                    @error('kode_klasifikasi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                    </div>



                    {{-- Section 3: Isi & Penanda Tangan --}}
                    <div class="p-6 md:p-8 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                            Isi & Validasi
                        </h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pembuat Draft <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-user-pen text-gray-400"></i>
                                    </div>
                                    <input type="text" name="pejabat_penandatangan"
                                        class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Pembuat Draft"
                                        value="{{ old('pejabat_penandatangan') }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Perihal / Keterangan</label>
                                <textarea name="perihal" rows="4"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Tuliskan perihal atau deskripsi singkat mengenai surat keputusan ini...">{{ old('perihal') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Upload File --}}
                    <div class="p-6 md:p-8 bg-gray-50/50">
                        <label class="block text-sm font-medium text-gray-700 mb-4">Upload Dokumen <span class="text-red-500">*</span></label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3 group-hover:text-blue-500 transition-colors"></i>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-blue-600">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-red-500 font-medium">PDF (Maks. 2MB)</p>
                                </div>
                                <input id="dropzone-file" name="file_pdf" type="file" accept="application/pdf" class="hidden" required />
                            </label>
                        </div>
                        <div id="fileNameDisplay" class="mt-2 text-sm text-gray-600 hidden flex items-center">
                            <i class="fa-solid fa-file-pdf text-red-500 mr-2"></i>
                            <span id="fileNameText">filename.pdf</span>
                        </div>
                        @error('file_pdf')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="p-6 md:p-8 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-4">
                        <a href="{{ route('sk.archive') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                            Batal
                        </a>
                        <button type="button" onclick="openConfirmModal()" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-sm flex items-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Simpan SK
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>



{{-- Modal Konfirmasi Simpan --}}
<div id="confirmModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all p-0 overflow-hidden">
            
            {{-- Header Modal --}}
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Konfirmasi Data
                </h3>
                <button type="button" onclick="closeConfirmModal()" class="text-blue-100 hover:text-white transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-md">
                    <p class="text-sm text-yellow-700 font-medium">
                        Mohon cek kembali data berikut sebelum menyimpan. Pastikan tidak ada kesalahan ketik.
                    </p>
                </div>

                <div class="space-y-4">
                    {{-- Review Item --}}
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jenis Surat</p>
                        <p class="text-gray-800 font-medium text-base" id="reviewJenisSurat">-</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Format Nomor SK</p>
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-100 text-gray-800 font-mono px-2 py-0.5 rounded text-sm font-bold border border-gray-200" id="reviewKode">-</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal</p>
                            <p class="text-gray-800 font-medium" id="reviewTanggal">-</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Perihal / Keterangan</p>
                        <p class="text-gray-800 font-medium bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm italic" id="reviewPerihal">-</p>
                    </div>
                     <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Pembuat Draft</p>
                        <p class="text-gray-800 font-medium" id="reviewPejabat">-</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition-colors">
                        Periksa Lagi
                    </button>
                    <button type="button" onclick="submitCreateForm()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm shadow-md transition-all flex justify-center items-center gap-2">
                        <span>Ya, Simpan SK</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pencarian Klasifikasi --}}
<div id="klasifikasiSearchModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeKlasifikasiModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all flex flex-col max-h-[85vh]">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
                <h3 class="text-lg font-bold text-gray-800">Cari Kode Klasifikasi</h3>
                <button type="button" onclick="closeKlasifikasiModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400 text-lg">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" id="modalSearchInput" 
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium shadow-sm"
                        placeholder="Ketik kata kunci (misal: Kepegawaian, Cuti, DL)..." autofocus>
                </div>
                <p class="text-xs text-gray-500 mt-2 ml-1">Tekan ENTER untuk mencari atau ketik minimal 2 karakter.</p>
            </div>

            {{-- Results List --}}
            <div class="flex-1 overflow-y-auto p-0 bg-gray-50/30" id="modalSearchResults">
                {{-- Initial State --}}
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <i class="fa-solid fa-keyboard text-4xl mb-3 opacity-20"></i>
                    <p class="text-sm">Mulai ketik untuk mencari kode.</p>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-right rounded-b-xl flex justify-between items-center">
                <span class="text-xs text-gray-400" id="resultCount"></span>
                <button type="button" onclick="closeKlasifikasiModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium text-sm shadow-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Simpan --}}
<div id="confirmModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all p-0 overflow-hidden">
            
            {{-- Header Modal --}}
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Konfirmasi Data
                </h3>
                <button type="button" onclick="closeConfirmModal()" class="text-blue-100 hover:text-white transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-md">
                    <p class="text-sm text-yellow-700 font-medium">
                        Mohon cek kembali data berikut sebelum menyimpan. Pastikan tidak ada kesalahan ketik.
                    </p>
                </div>

                <div class="space-y-4">
                    {{-- Review Item --}}
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jenis Surat</p>
                        <p class="text-gray-800 font-medium text-base" id="reviewJenisSurat">-</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Kode Klasifikasi</p>
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-100 text-gray-800 font-mono px-2 py-0.5 rounded text-sm font-bold border border-gray-200" id="reviewKode">-</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal</p>
                            <p class="text-gray-800 font-medium" id="reviewTanggal">-</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Perihal / Keterangan</p>
                        <p class="text-gray-800 font-medium bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm italic" id="reviewPerihal">-</p>
                    </div>
                     <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Pejabat Penandatangan</p>
                        <p class="text-gray-800 font-medium" id="reviewPejabat">-</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition-colors">
                        Periksa Lagi
                    </button>
                    <button type="button" onclick="submitCreateForm()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm shadow-md transition-all flex justify-center items-center gap-2">
                        <span>Ya, Simpan SK</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- CONFIRMATION MODAL LOGIC ---
    const confirmModal = document.getElementById('confirmModal');
    
    window.openConfirmModal = function() {
        // Validation Check (Manual Simple check)
        const form = document.getElementById('createSkForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Get Values
        const jenis = document.getElementById('jenisSuratSelect');
        const jenisText = jenis.options[jenis.selectedIndex].text;
        const kode = document.getElementById('kodeKlasifikasiInput').value;
        const perihal = document.getElementsByName('perihal')[0].value || '-';
        const pejabat = document.getElementsByName('pejabat_penandatangan')[0].value;
        const tanggal = document.getElementsByName('tanggal_ditetapkan')[0].value;
        const noAgenda = document.getElementById('nomorAgendaInput').value;

        // Static Office Code
        const kodeKantor = 'B7.33';

        // Construct Full SK Number Format
        // Format: {nomor_agenda}/{kode_kantor}/{kode_klasifikasi}
        const fullFormat = `${noAgenda}/${kodeKantor}/${kode}`;

        // Populate Modal
        document.getElementById('reviewJenisSurat').textContent = jenisText;
        document.getElementById('reviewKode').textContent = fullFormat; // Displpay Full Format here
        document.getElementById('reviewPerihal').textContent = perihal;
        document.getElementById('reviewPejabat').textContent = pejabat;
        document.getElementById('reviewTanggal').textContent = tanggal;

        // Show Modal
        confirmModal.classList.remove('hidden');
    };

    window.closeConfirmModal = function() {
        confirmModal.classList.add('hidden');
    };

    window.submitCreateForm = function() {
        document.getElementById('createSkForm').submit();
    };

document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.getElementById('jenisSuratSelect');
    const kodeKlasifikasiInput = document.getElementById('kodeKlasifikasiInput');
    const fileInput = document.getElementById('dropzone-file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText = document.getElementById('fileNameText');

    // File Input Name Display & Validation
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            if(this.files && this.files[0]) {
                const file = this.files[0];
                
                // Cek ukuran file (max 2MB)
                if(file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file melebihi batas 2MB. Silakan upload file yang lebih kecil.');
                    this.value = ''; // Reset input
                    fileNameDisplay.classList.add('hidden');
                    return;
                }
                
                fileNameText.textContent = file.name;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        });
    }

    // Event listener untuk perubahan jenis surat
    jenisSuratSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const selectedValue = this.value; // Jangan di-lowercase dulu utk cek exact value
        const kodeKlasifikasi = selectedOption.getAttribute('data-kode');
        const newJenisSuratField = document.getElementById('newJenisSuratField');
        const jenisSuratBaruInput = document.getElementById('jenisSuratBaruInput');

        // Reset state
        if (selectedValue === 'new_type_input') {
            newJenisSuratField.classList.remove('hidden');
            jenisSuratBaruInput.required = true;
            jenisSuratBaruInput.focus();
            
            // Clear kode klasifikasi agar user input sendiri
            kodeKlasifikasiInput.value = '';
            kodeKlasifikasiInput.placeholder = 'Masukkan kode klasifikasi baru...';
        } else {
            newJenisSuratField.classList.add('hidden');
            jenisSuratBaruInput.required = false;
            
            // Logic normal
            if (kodeKlasifikasi) {
                kodeKlasifikasiInput.value = kodeKlasifikasi;
            }
            kodeKlasifikasiInput.placeholder = 'Contoh: B.001';
        }
    });

    // --- KLASIFIKASI MODAL SEARCH LOGIC ---
    const searchModal = document.getElementById('klasifikasiSearchModal');
    const modalSearchInput = document.getElementById('modalSearchInput');
    const modalResults = document.getElementById('modalSearchResults');
    const resultCount = document.getElementById('resultCount');
    
    const searchInput = document.getElementById('kodeKlasifikasiInput'); // Main input
    const selectedInfo = document.getElementById('selectedKlasifikasiInfo');
    const selectedNama = document.getElementById('selectedNamaDisplay');
    const selectedUraian = document.getElementById('selectedUraianDisplay');
    const clearBtn = document.getElementById('clearKlasifikasiBtn');

    let debounceTimer;

    // Open Modal
    window.openKlasifikasiModal = function() {
        searchModal.classList.remove('hidden');
        modalSearchInput.value = '';
        modalResults.innerHTML = `
            <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                <i class="fa-solid fa-keyboard text-4xl mb-3 opacity-20"></i>
                <p class="text-sm">Mulai ketik untuk mencari kode.</p>
            </div>
        `;
        resultCount.textContent = '';
        setTimeout(() => modalSearchInput.focus(), 100);
        document.body.style.overflow = 'hidden'; // Prevent scroll
    };

    // Close Modal
    window.closeKlasifikasiModal = function() {
        searchModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scroll
    };

    // Debounced Search in Modal
    modalSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            return;
        }

        modalResults.innerHTML = `
            <div class="flex items-center justify-center h-32 text-blue-500">
                <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
            </div>
        `;

        debounceTimer = setTimeout(() => {
            fetch(`/klasifikasi/search?term=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    renderResults(data);
                })
                .catch(err => {
                    modalResults.innerHTML = `<p class="p-4 text-center text-red-500 text-sm">Terjadi kesalahan. Silakan coba lagi.</p>`;
                });
        }, 300);
    });

    function renderResults(data) {
        modalResults.innerHTML = '';
        resultCount.textContent = `Ditemukan ${data.length} hasil`;

        if (data.length === 0) {
            modalResults.innerHTML = `
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-20"></i>
                    <p class="text-sm">Tidak ditemukan hasil yang cocok.</p>
                </div>
            `;
            return;
        }

        const list = document.createElement('div');
        list.className = 'divide-y divide-gray-100 bg-white';

        data.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full text-left px-6 py-3 hover:bg-blue-50 transition-colors flex flex-col gap-1 group';
            btn.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-mono font-bold text-blue-600 bg-blue-50 group-hover:bg-white px-2 py-0.5 rounded text-xs border border-blue-100">${item.kode}</span>
                </div>
                <p class="text-sm font-medium text-gray-800">${item.nama}</p>
                ${item.uraian ? `<p class="text-xs text-gray-500 line-clamp-1">${item.uraian}</p>` : ''}
            `;
            
            btn.addEventListener('click', () => {
                selectKlasifikasi(item);
                closeKlasifikasiModal();
            });
            list.appendChild(btn);
        });

        modalResults.appendChild(list);
    }

    // Function saat item dipilih
    function selectKlasifikasi(item) {
        searchInput.value = item.kode;
        selectedNama.textContent = item.nama;
        selectedUraian.textContent = item.uraian || '-';
        
        selectedInfo.classList.remove('hidden');
        
        // Highlight input
        searchInput.classList.add('border-green-500', 'ring-1', 'ring-green-500');
        setTimeout(() => {
            searchInput.classList.remove('border-green-500', 'ring-1', 'ring-green-500');
        }, 1000);
    }

    // Clear Selection
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        selectedInfo.classList.add('hidden');
        searchInput.focus();
    });
    
    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
            closeKlasifikasiModal();
        }
    });

    // Inisialisasi saat halaman dimuat
    if (jenisSuratSelect.value) {
         const selectedOption = jenisSuratSelect.options[jenisSuratSelect.selectedIndex];
         const kodeKlasifikasi = selectedOption.getAttribute('data-kode');

         if (kodeKlasifikasi && !kodeKlasifikasiInput.value) {
            kodeKlasifikasiInput.value = kodeKlasifikasi;
         }
    }
});
</script>
@endsection