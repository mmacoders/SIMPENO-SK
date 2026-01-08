@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    @include('component.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8 bg-gray-50/50">
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-blue-900 tracking-tight flex items-center">
                <i class="fa-solid fa-certificate mr-3 text-blue-600"></i>
                Arsip Sertifikat
            </h2>
            <p class="text-gray-500 mt-1 ml-11">Kelola dan pantau daftar sertifikat yang telah diterbitkan.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-blue-100 flex items-center justify-between">
                <div>
                     <p class="text-sm font-medium text-gray-500">Total Batch Sertifikat</p>
                     <h3 class="text-3xl font-bold text-gray-800">{{ $totalSertifikat }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xl">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-purple-100 flex items-center justify-between">
                <div>
                     <p class="text-sm font-medium text-gray-500">Total Penerima (Estimasi)</p>
                     <h3 class="text-3xl font-bold text-gray-800">{{ $totalPenerima }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-blue-50 text-blue-800 uppercase text-xs font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-2xl">Nomor Agenda (SK)</th>
                            <th class="px-6 py-4">Range Nomor Sertifikat</th>
                            <th class="px-6 py-4 text-center">Jumlah</th>
                            <th class="px-6 py-4">Tanggal Terbit</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dataSertifikat as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $item->nomor_sk }}
                                <div class="text-xs text-gray-500 font-normal mt-0.5">{{ $item->perihal ?? 'Tanpa perihal' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-mono font-semibold">
                                    {{ $item->nomor_sertifikat ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $item->jumlah_penerima ?? 1 }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal_ditetapkan)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Lihat PDF --}}
                                    @if($item->file_pdf)
                                    <a href="{{ route('sk.view', $item->id) }}" target="_blank" 
                                       class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors tooltip" 
                                       title="Lihat File Asli">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                    @endif
                                    
                                    {{-- Tombol Detail List --}}
                                    <button onclick="showCertificateList('{{ $item->nomor_sertifikat }}', '{{ $item->jumlah_penerima ?? 1 }}')"
                                        class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors tooltip"
                                        title="Lihat Daftar Nomor">
                                        <i class="fa-solid fa-list-ol"></i>
                                    </button>

                                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $item->user_id))
                                    {{-- Edit Button --}}
                                    <button type="button" data-id="{{ $item->id }}" data-nomor_sk="{{ $item->nomor_sk }}"
                                        data-jenis_surat="{{ $item->jenis_surat }}" data-kode_klasifikasi="{{ $item->kode_klasifikasi }}"
                                        data-tanggal_ditetapkan="{{ $item->tanggal_ditetapkan->format('Y-m-d') }}"
                                        data-pejabat_penandatangan="{{ $item->pejabat_penandatangan }}"
                                        data-perihal="{{ $item->perihal ?? '' }}" data-file_pdf="{{ $item->file_pdf ?? '' }}"
                                        data-jumlah_penerima="{{ $item->jumlah_penerima ?? 1 }}"
                                        class="btn-edit w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors tooltip"
                                        title="Edit Sertifikat">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button type="button" data-id="{{ $item->id }}" data-judul="{{ $item->nomor_sk }}"
                                        class="btn-delete w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors tooltip"
                                        title="Hapus Sertifikat">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-certificate text-3xl mb-3 opacity-50"></i>
                                <p>Belum ada data sertifikat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Detail List --}}
        <div id="certListModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="closeListModal()"></div>
            
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden flex flex-col max-h-[80vh]">
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-blue-900">Daftar Nomor Sertifikat</h3>
                        <button onclick="closeListModal()" class="text-blue-400 hover:text-blue-600">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    {{-- List Content --}}
                    <div class="p-0 overflow-y-auto flex-1">
                        <ul id="certListContainer" class="divide-y divide-gray-100 text-sm">
                            {{-- Items generated via JS --}}
                        </ul>
                    </div>
                    
                    {{-- Footer --}}
                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-right">
                        <button onclick="closeListModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- Modal Edit --}}
        <div id="editModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeEditModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all">
                    <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-800">Edit Sertifikat</h2>
                            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        <form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('sk.update') }}">
                            @csrf
                            <input type="hidden" name="id" id="edit_id">
                            
                            {{-- Hidden fields yang tidak perlu diubah, tapi required --}}
                            <input type="hidden" name="jenis_surat" id="edit_jenis_surat">
                            <input type="hidden" name="kode_klasifikasi" id="edit_kode_klasifikasi">

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SK (Induk)</label>
                                    <input type="text" name="nomor_sk" id="edit_nomor_sk" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Nomor SK Induk tidak dapat diubah di sini.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Penerima</label>
                                    <input type="number" name="jumlah_penerima" id="edit_jumlah_penerima" min="1" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <p class="text-xs text-blue-600 mt-1">Mengubah jumlah akan mereset range nomor secara otomatis.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_ditetapkan" id="edit_tanggal_ditetapkan" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pejabat Penandatangan</label>
                                    <input type="text" name="pejabat_penandatangan" id="edit_pejabat_penandatangan" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                                    <textarea name="perihal" id="edit_perihal" rows="3"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Update File PDF (Opsional)</label>
                                    <input type="file" name="file_pdf" accept="application/pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah file.</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                                <button type="button" onclick="closeEditModal()"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Modal Delete --}}
        <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeDeleteModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm transform transition-all">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Sertifikat?</h3>
                        <p class="text-sm text-gray-500 mb-6">Anda akan menghapus Sertifikat <span id="delete_judul" class="font-bold text-gray-800"></span>. Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <div id="deleteFormContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function showCertificateList(rangeString, count) {
    const listContainer = document.getElementById('certListContainer');
    listContainer.innerHTML = ''; // Reset
    
    // Parse Range logic simple
    // Format: "SERTIFIKAT-001 s/d SERTIFIKAT-005" OR "SERTIFIKAT-001"
    
    let items = [];
    count = parseInt(count);

    if (rangeString.includes('s/d')) {
        const parts = rangeString.split('s/d');
        const startStr = parts[0].trim(); // SERTIFIKAT-001
        
        // Extract prefix and number
        // Asumsi format: PREFIX-NUMBER (SERTIFIKAT-001)
        // Cari angka di akhir string
        const match = startStr.match(/^(.*?)(\d+)$/);
        
        if (match) {
            const prefix = match[1];
            const startNumStr = match[2];
            const startNum = parseInt(startNumStr);
            const digits = startNumStr.length;
            
            for(let i = 0; i < count; i++) {
                const currentNum = startNum + i;
                const currentStr = prefix + String(currentNum).padStart(digits, '0');
                items.push(currentStr);
            }
        } else {
            // Fallback jika format tidak standar
            items.push(rangeString); 
        }
    } else {
        // Single item
        items.push(rangeString);
    }

    // Render Items
    items.forEach((code, index) => {
        const li = document.createElement('li');
        li.className = 'px-6 py-3 flex items-center hover:bg-blue-50/50';
        li.innerHTML = `
            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-3">${index + 1}</span>
            <span class="font-mono text-gray-700 font-medium">${code}</span>
        `;
        listContainer.appendChild(li);
    });

    // Show Modal
    const modal = document.getElementById('certListModal');
    modal.classList.remove('hidden');
}


// --- Modal Edit Logic ---
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        // Ambil data dari atribut tombol
        const id = this.dataset.id;
        const nomor_sk = this.dataset.nomor_sk;
        const jumlah_penerima = this.dataset.jumlah_penerima;
        const tanggal_ditetapkan = this.dataset.tanggal_ditetapkan;
        const pejabat_penandatangan = this.dataset.pejabat_penandatangan;
        const perihal = this.dataset.perihal;
        
        // Isi form
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nomor_sk').value = nomor_sk;
        document.getElementById('edit_jumlah_penerima').value = jumlah_penerima;
        document.getElementById('edit_tanggal_ditetapkan').value = tanggal_ditetapkan;
        document.getElementById('edit_pejabat_penandatangan').value = pejabat_penandatangan;
        document.getElementById('edit_perihal').value = perihal;
        
        // Hidden fields required
        document.getElementById('edit_jenis_surat').value = this.dataset.jenis_surat;
        document.getElementById('edit_kode_klasifikasi').value = this.dataset.kode_klasifikasi;

        // Buka modal
        document.getElementById('editModal').classList.remove('hidden');
    });
});

// --- Modal Delete Logic ---
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const judul = this.dataset.judul;
        
        document.getElementById('delete_judul').textContent = judul;
        
        const formHtml = `
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm">Batal</button>
                <form action="/sk/destroy/${id}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm shadow-sm">Ya, Hapus</button>
                </form>
            </div>
        `;
        
        document.getElementById('deleteFormContainer').innerHTML = formHtml;
        document.getElementById('deleteModal').classList.remove('hidden');
    });
});

</script>

@endsection
