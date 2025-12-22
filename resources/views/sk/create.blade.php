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
                                        </select>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Terakhir</label>
                                <input type="text" id="lastCertificateNumber"
                                    class="block w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500 shadow-sm sm:text-sm"
                                    value="{{ $lastCertificateNumber ?? 'Belum ada sertifikat' }}" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Sertifikat Baru</label>
                                <input type="text" name="nomor_sertifikat" id="newCertificateNumber"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Generate otomatis..." value="{{ old('nomor_sertifikat') }}">
                                <p class="text-xs text-blue-600 mt-1"><i class="fa-solid fa-circle-info mr-1"></i> Akan diisi otomatis oleh sistem</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Penerima (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-users text-gray-400"></i>
                                    </div>
                                    <input type="number" name="jumlah_penerima" min="1"
                                        class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Masukkan total penerima..." value="{{ old('jumlah_penerima') }}">
                                </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-4">Upload Dokumen (Opsional)</label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3 group-hover:text-blue-500 transition-colors"></i>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-blue-600">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PDF (Maks. 2MB)</p>
                                </div>
                                <input id="dropzone-file" name="file_pdf" type="file" accept="application/pdf" class="hidden" />
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
</div>

{{-- JavaScript untuk menangani kolom dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.getElementById('jenisSuratSelect');
    const sertifikatFields = document.getElementById('sertifikatFields');
    const lastCertificateNumber = document.getElementById('lastCertificateNumber');
    const newCertificateNumber = document.getElementById('newCertificateNumber');
    const kodeKlasifikasiInput = document.getElementById('kodeKlasifikasiInput');
    const fileInput = document.getElementById('dropzone-file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText = document.getElementById('fileNameText');

    // File Input Name Display
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            if(this.files && this.files[0]) {
                fileNameText.textContent = this.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        });
    }

    // Fungsi untuk update nomor sertifikat baru
    function updateCertificateNumber() {
        if (lastCertificateNumber.value && lastCertificateNumber.value !== 'Belum ada sertifikat') {
            // Ekstrak angka dari nomor terakhir dan tambahkan 1
            const lastNum = lastCertificateNumber.value.match(/\d+/);
            if (lastNum) {
                const nextNum = parseInt(lastNum[0]) + 1;
                const paddedNum = String(nextNum).padStart(3, '0'); // Format 001, 002, dst
                newCertificateNumber.value = `SERTIFIKAT-${paddedNum}`;
            }
        } else {
            // Jika belum ada sertifikat, mulai dari 001
            newCertificateNumber.value = 'SERTIFIKAT-001';
        }
    }

    // Event listener untuk perubahan jenis surat
    jenisSuratSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const selectedValue = this.value.toLowerCase();
        const kodeKlasifikasi = selectedOption.getAttribute('data-kode');

        // Update Kode Klasifikasi jika ada
        if (kodeKlasifikasi) {
            kodeKlasifikasiInput.value = kodeKlasifikasi;
        }

        // Tampilkan/sembunyikan kolom sertifikat
        if (selectedValue.includes('sertifikat')) {
            sertifikatFields.classList.remove('hidden');
            updateCertificateNumber();
        } else {
            sertifikatFields.classList.add('hidden');
            newCertificateNumber.value = '';
        }
    });

    // Inisialisasi saat halaman dimuat (jika ada nilai sebelumnya)
    if (jenisSuratSelect.value) {
         const selectedOption = jenisSuratSelect.options[jenisSuratSelect.selectedIndex];
         const kodeKlasifikasi = selectedOption.getAttribute('data-kode');

         // Hanya isi otomatis jika input kode masih kosong (hindari override old input)
         if (kodeKlasifikasi && !kodeKlasifikasiInput.value) {
            kodeKlasifikasiInput.value = kodeKlasifikasi;
         }

        if (jenisSuratSelect.value.toLowerCase().includes('sertifikat')) {
            sertifikatFields.classList.remove('hidden');
            updateCertificateNumber();
        }
    }
});
</script>
@endsection