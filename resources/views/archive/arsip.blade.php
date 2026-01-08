@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    @include('component.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8 bg-gray-50/50">
        @if(session('error'))
        <div class="animate-fade-in-down mb-6 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="animate-fade-in-down mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Arsip Surat Keputusan</h2>
                <p class="text-gray-500 mt-1">Kelola dan telusuri semua arsip surat keputusan yang telah dibuat</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                 {{-- 🔍 Search --}}
                <div class="relative flex-1 md:flex-initial md:w-64">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input id="filterQ" type="text" placeholder="Cari nomor, perihal..."
                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:outline-none shadow-sm text-sm transition-all">
                </div>

                {{-- ⚙️ Filter Button --}}
                <div class="relative group" id="filterContainer">
                    <button id="toggleFilterBtn" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-200 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-100 text-sm font-medium flex items-center">
                        <i class="fa-solid fa-filter mr-2 text-gray-400"></i>
                        Filter
                        <i class="fa-solid fa-chevron-down ml-2 text-xs text-gray-400"></i>
                    </button>

                    {{-- Dropdown Filter Content --}}
                    <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-xl shadow-2xl z-50 p-5 animate-fade-in-up">
                        
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-50">
                             <span class="font-semibold text-gray-800 text-sm">Filter Data</span>
                             <button type="button" id="closeFilterBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-times"></i>
                             </button>
                        </div>
        
                        {{-- Filter Date --}}
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Spesifik</label>
                            <input type="date" id="filterDate" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all">
                        </div>

                        {{-- Filter Jenis Surat --}}
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis Surat</label>
                            <select id="filterJenis" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all">
                                <option value="">Semua Jenis</option>
                                @foreach($kategoriSK as $kat)
                                <option value="{{ $kat->jenis_surat }}">{{ $kat->jenis_surat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end pt-4 mt-2 border-t border-gray-50 gap-2">
                            <button id="resetFilterBtn" class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md font-medium transition-colors">
                                Reset
                            </button>
                            <button id="applyFilterBtn" class="px-4 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-shadow shadow-sm font-medium">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Arsip SK -->
        <div class="space-y-4" id="skList">
            @forelse ($dataSK as $sk)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-md transition-all duration-200 sk-item group"
                data-year="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('Y') }}"
                data-month="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('m') }}"
                data-date="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('Y-m-d') }}"
                data-keywords="{{ $sk->nomor_sk }} {{ $sk->jenis_surat }} {{ $sk->pejabat_penandatangan }} {{ $sk->perihal }} {{ $sk->kode_klasifikasi }}">

                <div class="flex flex-col lg:flex-row justify-between gap-6">
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $sk->nomor_sk }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $sk->jenis_surat }}
                                    </span>
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-3 py-1 rounded-full text-xs font-semibold font-mono">
                                        {{ $sk->kode_klasifikasi }}
                                    </span>
                                    @if(!empty($sk->file_pdf))
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-file mr-1"></i> Dokumen Tersedia
                                    </span>
                                    @else
                                    <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-file-slash mr-1"></i> Tanpa Dokumen
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-calendar text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Tanggal</p>
                                    <p class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Dibuat Oleh</p>
                                    <p class="text-gray-700 font-medium">{{ $sk->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fa-solid fa-signature text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Penandatangan</p>
                                    <p class="text-gray-700 font-medium">{{ $sk->pejabat_penandatangan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Perihal -->
                        @if($sk->perihal)
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <p class="text-xs text-gray-500 font-semibold mb-1">Perihal:</p>
                            <p class="text-sm text-gray-700">{{ $sk->perihal }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex lg:flex-col gap-2 lg:min-w-[140px]">
                        {{-- Logika Tombol Lihat --}}
                        @if(str_contains(strtolower($sk->jenis_surat), 'sertifikat'))
                             <a href="{{ route('sertifikat.index') }}" 
                                class="flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-300 shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-list-check"></i>
                                <span>Detail</span>
                            </a>
                        @elseif(!empty($sk->file_pdf))
                            <button type="button" data-id="{{ $sk->id }}"
                                class="btn-view flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-300 shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-eye"></i>
                                <span>Lihat</span>
                            </button>
                        @else
                            <button type="button" disabled
                                class="flex items-center justify-center gap-2 bg-gray-100 text-gray-400 px-4 py-2.5 rounded-lg font-medium text-sm cursor-not-allowed">
                                <i class="fa-solid fa-eye-slash"></i>
                                <span>Tidak Ada</span>
                            </button>
                        @endif

                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $sk->user_id))
                        <!-- Edit Button -->
                        <button type="button" data-id="{{ $sk->id }}" data-nomor_sk="{{ $sk->nomor_sk }}"
                            data-jenis_surat="{{ $sk->jenis_surat }}" data-kode_klasifikasi="{{ $sk->kode_klasifikasi }}"
                            data-tanggal_ditetapkan="{{ $sk->tanggal_ditetapkan->format('Y-m-d') }}"
                            data-pejabat_penandatangan="{{ $sk->pejabat_penandatangan }}"
                            data-perihal="{{ $sk->perihal ?? '' }}" data-file_pdf="{{ $sk->file_pdf ?? '' }}"
                            class="btn-edit flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit</span>
                        </button>

                        <!-- Delete Button -->
                        <button type="button" data-id="{{ $sk->id }}" data-judul="{{ $sk->nomor_sk }}"
                            class="btn-delete flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium mb-2">Belum ada Surat Keputusan</p>
                <p class="text-gray-400 text-sm mb-6">Mulai dengan membuat surat keputusan pertama Anda</p>
                <a href="{{ route('sk.create') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-all">
                    <i class="fa-solid fa-plus"></i>Buat SK Pertama
                </a>
            </div>
            @endforelse
        </div>

        <div id="noResult" class="hidden bg-white border border-gray-100 rounded-2xl p-12 text-center mt-4">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-lg font-medium">Tidak ada hasil yang ditemukan</p>
            <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci atau filter pencarian Anda</p>
        </div>



        <!-- Modal Edit -->
        <div id="editModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <!-- Modal Content -->
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all">
                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-800">Edit Surat Keputusan</h2>
                            <button type="button" id="btnCloseModal"
                                class="text-gray-400 hover:text-gray-600 text-xl rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors duration-200">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        <form id="editForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('sk.update') }}">
                            @csrf
                            @method('POST')

                            <input type="hidden" name="id" id="edit_id">

                            <div class="space-y-4">
                                <!-- NOMOR SK -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SK </label>
                                    <input type="text" name="nomor_sk" id="edit_nomor_sk" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                </div>

                                <!-- KODE KLASIFIKASI -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Klasifikasi</label>
                                    <input type="text" name="kode_klasifikasi" id="edit_kode_klasifikasi" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                </div>

                                <!-- JENIS SURAT -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat </label>
                                    <select name="jenis_surat" id="edit_jenis_surat" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                        <option value="">Pilih Jenis Surat</option>
                                        @foreach($kategoriSK ?? [] as $kategori)
                                        <option value="{{ $kategori->jenis_surat }}">{{ $kategori->jenis_surat }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- TANGGAL DITETAPKAN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                        Ditetapkan</label>
                                    <input type="date" name="tanggal_ditetapkan" id="edit_tanggal_ditetapkan" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                </div>

                                <!-- PEJABAT PENANDATANGAN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pembuat </label>
                                    <input type="text" name="pejabat_penandatangan" id="edit_pejabat_penandatangan"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                </div>

                                <!-- PERIHAL -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Perihal</label>
                                    <textarea name="perihal" id="edit_perihal" rows="3"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200"></textarea>
                                </div>

                                <!-- FILE PDF -->
                                <div id="edit_file_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File PDF
                                        (Opsional)</label>
                                    <input type="file" name="file_pdf" accept="application/pdf" id="edit_file_pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors duration-200">
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">File saat ini: <span id="current_file"
                                                class="font-medium text-blue-600"></span></p>
                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file.
                                            Maksimal 2MB, format PDF.</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                        <div class="flex justify-end gap-3">
                            <button type="button" id="btnCancelModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors duration-200">
                                Batal
                            </button>
                            <button type="submit" form="editForm"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 font-medium transition-all duration-300">
                                <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div id="deleteModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <!-- Modal Content -->
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-red-600">Konfirmasi Hapus</h2>
                            <button type="button" id="btnCloseDeleteModal"
                                class="text-gray-400 hover:text-gray-600 text-xl rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors duration-200">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 mb-4">
                            Apakah Anda yakin ingin menghapus SK berikut?
                        </p>

                        <div class="mb-4 p-3 bg-red-50 rounded-lg">
                            <p class="text-sm text-gray-700">
                                <strong>Nomor SK:</strong>
                                <span id="delete_judul" class="font-semibold"></span>
                            </p>
                            <p class="text-xs text-red-500 mt-1">
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>

                        <!-- Form akan dibuat dinamis oleh JavaScript -->
                        <div id="deleteFormContainer">
                            <!-- Form akan dimasukkan di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<style>
/* Prevent scrolling when modal is open */
body.modal-open {
    overflow: hidden;
    padding-right: 15px;
    /* Prevent layout shift when scrollbar disappears */
}

/* Smooth modal animation */
.modal-enter {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>

<script>
// Fungsi untuk mencegah scrolling background
function preventBackgroundScroll(prevent) {
    const body = document.body;
    if (prevent) {
        body.classList.add('modal-open');
    } else {
        body.classList.remove('modal-open');
    }
}

// Fungsi utama untuk filter & searching
function applyFilters() {
    const q = $('#filterQ').val().toLowerCase().trim();
    const dateValue = $('#filterDate').val();
    const jenisValue = ($('#filterJenis').val() || '').toLowerCase();

    let anyVisible = false;

    $('#skList .sk-item').each(function() {
        const $item = $(this);
        // Pastikan format date di dataset sesuai YYYY-MM-DD
        // Kita butuh menambahkan data-date di element HTML nanti
        const itemDate = $item.data('date'); // format YYYY-MM-DD
        const keywords = String($item.data('keywords')).toLowerCase();

        let visible = true;

        // Filter keyword
        if (q && !keywords.includes(q)) {
            visible = false;
        }

        // Filter tanggal
        if (dateValue && itemDate !== dateValue) {
            visible = false;
        }

        // Filter jenis surat
        if (jenisValue && !keywords.includes(jenisValue)) {
             visible = false;
        }

        if (visible) {
            $item.show();
            anyVisible = true;
        } else {
            $item.hide();
        }
    });

    // Tampilkan pesan jika tidak ada hasil
    if (anyVisible) {
        $('#noResult').addClass('hidden');
    } else {
        $('#noResult').removeClass('hidden');
    }
}

// Logic Dropdown Filter
$(document).ready(function() {
    const $filterDropdown = $('#filterDropdown');
    const $toggleFilterBtn = $('#toggleFilterBtn');

    // Toggle dropdown
    $toggleFilterBtn.on('click', function(e) {
        e.stopPropagation();
        $filterDropdown.toggleClass('hidden');
    });

    // Close dropdown button
    $('#closeFilterBtn').on('click', function() {
        $filterDropdown.addClass('hidden');
    });

    // Close dropdown saat klik di luar
    $(document).on('click', function(e) {
        if (!$filterDropdown.is(e.target) && $filterDropdown.has(e.target).length === 0 && !$toggleFilterBtn.is(e.target)) {
            $filterDropdown.addClass('hidden');
        }
    });

    // Prevent close saat klik di dalam dropdown
    // (Sudah tercover di logic atas tapi untuk safety)
    $filterDropdown.on('click', function(e) {
        e.stopPropagation();
    });

    // Apply Filter Button
    $('#applyFilterBtn').on('click', function() {
        applyFilters();
        $filterDropdown.addClass('hidden');
    });

    // Reset Filter Button
    $('#resetFilterBtn').on('click', function() {
        $('#filterDate').val('');
        $('#filterJenis').val('');
        $('#filterQ').val(''); // Opsional: reset search juga atau tidak
        applyFilters();
    });
    
    // Auto search saat ngetik (live search)
    $('#filterQ').on('keyup', function() {
        applyFilters();
    });
});

// Lihat SK di tab baru
$(document).on('click', '.btn-view', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    if (id) {
        window.open(`{{ route('download.pdf') }}?id=${id}`, '_blank');
    }
});

// Edit SK - PERBAIKI NAMA FIELD
$(document).on('click', '.btn-edit', function() {
    const id = $(this).data('id');
    const nomor_sk = $(this).data('nomor_sk') || ''; // data-judul sebenarnya adalah nomor_sk
    const jenis_surat = $(this).data('jenis_surat') || ''; // data-kategori sebenarnya adalah jenis_surat
    const kode_klasifikasi = $(this).data('kode_klasifikasi') || '';
    const tanggal_ditetapkan = $(this).data('tanggal_ditetapkan') || '';
    const pejabat_penandatangan = $(this).data('pejabat_penandatangan') || '';
    const perihal = $(this).data('perihal') || ''; // data-keterangan sebenarnya adalah perihal

    console.log('Edit data:', {
        id,
        nomor_sk,
        jenis_surat,
        kode_klasifikasi,
        tanggal_ditetapkan,
        pejabat_penandatangan,
        perihal
    });

    // Isi form edit dengan ID yang BENAR
    $('#edit_id').val(id);
    $('#edit_nomor_sk').val(nomor_sk);
    $('#edit_kode_klasifikasi').val(kode_klasifikasi);
    $('#edit_jenis_surat').val(jenis_surat);
    $('#edit_tanggal_ditetapkan').val(tanggal_ditetapkan);
    $('#edit_pejabat_penandatangan').val(pejabat_penandatangan);
    $('#edit_perihal').val(perihal);

    // LOGIC HIDE UPLOAD UTK SERTIFIKAT
    if (jenis_surat.toLowerCase().includes('sertifikat')) {
        $('#edit_file_container').hide();
    } else {
        $('#edit_file_container').show();
        // Reset value input file jika perlu (opsional, browser security prevent overriding value to empty string cleanly besides reset form)
        // Tapi tampilan current file dll biarkan saja logic backend yg handle
    }

    // Tampilkan modal dan prevent scroll
    $('#editModal').removeClass('hidden');
    preventBackgroundScroll(true);

    // Tambahkan animasi
    $('#editModal .relative').addClass('modal-enter');
});

// Close modal edit
function closeEditModal() {
    $('#editModal').addClass('hidden');
    preventBackgroundScroll(false);
}

$('#btnCloseModal, #btnCancelModal').click(closeEditModal);

// Close modal ketika klik overlay
$('#editModal > .fixed.inset-0').click(closeEditModal);

// Prevent closing modal ketika klik di dalam konten modal
$('#editModal .relative').click(function(e) {
    e.stopPropagation();
});

// Event: ketik di kolom search -> live searching
$('#filterQ').on('keyup', function() {
    applyFilters();
});

// Event: ganti tahun/bulan -> auto filter
$('#filterYear, #filterMonth').on('change', function() {
    applyFilters();
});

// Hapus SK - Buat form dinamis
$(document).on('click', '.btn-delete', function() {
    const id = $(this).data('id');
    const judul = $(this).data('judul');

    // Buat form hapus dengan URL yang benar
    const formHtml = `
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="btnCancelDelete"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors duration-200">
                Batal
            </button>
            <form method="POST" action="/sk/destroy/${id}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors duration-200">
                    <i class="fa-solid fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    `;

    // Masukkan form ke container
    $('#deleteFormContainer').html(formHtml);
    $('#delete_judul').text(judul);

    // Tampilkan modal dan prevent scroll
    $('#deleteModal').removeClass('hidden');
    preventBackgroundScroll(true);

    // Tambahkan animasi
    $('#deleteModal .relative').addClass('modal-enter');
});


// ======================

// Close modal hapus
function closeDeleteModal() {
    $('#deleteModal').addClass('hidden');
    preventBackgroundScroll(false);
}

$(document).on('click', '#btnCancelDelete', closeDeleteModal);
$('#btnCloseDeleteModal').click(closeDeleteModal);

// Close modal ketika klik overlay
$('#deleteModal > .fixed.inset-0').click(closeDeleteModal);

// Prevent closing modal ketika klik di dalam konten modal
$('#deleteModal .relative').click(function(e) {
    e.stopPropagation();
});

// Handle form submission dengan loading state
$('#editForm').on('submit', function() {
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Menyimpan...');
});

// Handle form hapus submission dengan loading state (gunakan event delegation)
$(document).on('submit', '#deleteFormContainer form', function() {
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Menghapus...');
});

// Apply filter saat page load
$(document).ready(function() {
    applyFilters();

    // Close modal dengan ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!$('#editModal').hasClass('hidden')) {
                closeEditModal();
            }
            if (!$('#deleteModal').hasClass('hidden')) {
                closeDeleteModal();
            }
        }
    });
});
</script>
@endsection