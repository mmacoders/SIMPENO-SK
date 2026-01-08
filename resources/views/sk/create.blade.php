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

            <form action="{{ route('sk.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <input type="text"
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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Klasifikasi <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa-solid fa-code text-gray-400"></i>
                                        </div>
                                        <input type="text" name="kode_klasifikasi" id="kodeKlasifikasiInput"
                                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            value="{{ old('kode_klasifikasi') }}" placeholder="Contoh: B.001" required>
                                    </div>
                                    @error('kode_klasifikasi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Khusus Sertifikat (Dinamis) --}}
                    <div id="sertifikatFields" class="hidden bg-blue-50/50 border-b border-blue-100 p-6 md:p-8">
                        <h2 class="text-lg font-semibold text-blue-800 mb-6 flex items-center">
                            <i class="fa-solid fa-certificate mr-3"></i>
                            Informasi Sertifikat
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Sertifikat Terakhir</label>
                                <input type="number" name="jumlah_sertifikat" id="jumlahSertifikatInput" min="1"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Masukkan jumlah sertifikat..." value="{{ old('jumlah_sertifikat') }}">
                                <p class="text-xs text-gray-500 mt-1">Masukkan jumlah sertifikat yang akan dibuat.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Otomatis</label>
                                <input type="text" id="previewKodeOtomatis"
                                    class="block w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500 shadow-sm sm:text-sm"
                                    value="SERTIFIKAT-..." readonly>
                                <p class="text-xs text-blue-600 mt-1" id="previewRangeText">Akan mulai dari 0 - n</p>
                            </div>
                            
                            <!-- Hidden input untuk menyimpan nomor start/last (optional, jika perlu di controller) -->
                            <input type="hidden" id="lastCertNumDb" value="{{ $lastCertificateNumber ?? 'SERTIFIKAT-000' }}">
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pejabat Penandatangan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-user-pen text-gray-400"></i>
                                    </div>
                                    <input type="text" name="pejabat_penandatangan"
                                        class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Nama atau Jabatan Penandatangan"
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
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-sm flex items-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Simpan SK
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>



{{-- JavaScript untuk menangani kolom dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.getElementById('jenisSuratSelect');
    const sertifikatFields = document.getElementById('sertifikatFields');
    const jumlahSertifikatInput = document.getElementById('jumlahSertifikatInput');
    const previewKodeOtomatis = document.getElementById('previewKodeOtomatis');
    const previewRangeText = document.getElementById('previewRangeText');
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

    // Fungsi untuk update preview range sertifikat
    function updateCertificatePreview() {
        const count = parseInt(jumlahSertifikatInput.value) || 0;
        
        if (count > 0) {
            // Start from 1 as requested
            previewKodeOtomatis.value = `SERTIFIKAT-{1} s/d SERTIFIKAT-{${count}}`;
            previewRangeText.textContent = `Total ${count} sertifikat akan dibuat.`;
        } else {
            previewKodeOtomatis.value = 'SERTIFIKAT-...';
            previewRangeText.textContent = 'Masukkan jumlah untuk melihat range.';
        }
    }

    // Event listener jumlah sertifikat
    if (jumlahSertifikatInput) {
        jumlahSertifikatInput.addEventListener('input', updateCertificatePreview);
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
            
            // Hide sertifikat fields sementara (logic sertifikat hanya untuk yg sdh ada keywordnya)
            // Kecuali user ngetik "Sertifikat xxx" nanti? 
            // Untuk simplifikasi, anggap input baru belum trigger fitur sertifikat kecuali manual logic, 
            // tapi kita hide dulu biar clear.
            sertifikatFields.classList.add('hidden');
        } else {
            newJenisSuratField.classList.add('hidden');
            jenisSuratBaruInput.required = false;
            
            // Logic normal
            if (kodeKlasifikasi) {
                kodeKlasifikasiInput.value = kodeKlasifikasi;
            }
            kodeKlasifikasiInput.placeholder = 'Contoh: B.001';

            // Tampilkan/sembunyikan kolom sertifikat
            if (selectedValue.toLowerCase().includes('sertifikat')) {
                sertifikatFields.classList.remove('hidden');
                updateCertificatePreview();
            } else {
                sertifikatFields.classList.add('hidden');
            }
        }
    });

    // Inisialisasi saat halaman dimuat
    if (jenisSuratSelect.value) {
         const selectedOption = jenisSuratSelect.options[jenisSuratSelect.selectedIndex];
         const kodeKlasifikasi = selectedOption.getAttribute('data-kode');

         if (kodeKlasifikasi && !kodeKlasifikasiInput.value) {
            kodeKlasifikasiInput.value = kodeKlasifikasi;
         }

        if (jenisSuratSelect.value.toLowerCase().includes('sertifikat')) {
            sertifikatFields.classList.remove('hidden');
            updateCertificatePreview();
        }
    }
});
</script>
@endsection