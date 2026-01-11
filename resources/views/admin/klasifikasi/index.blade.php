@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    @include('component.sidebar')

    <main class="flex-1 p-8 bg-gray-50 min-h-screen">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Manajemen Klasifikasi Arsip</h1>
                <p class="text-gray-500 mt-1">Kelola kode dan nama klasifikasi arsip surat.</p>
            </div>
            <button onclick="openModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium flex items-center transition-all">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Klasifikasi
            </button>
        </div>

        {{-- Alert Success/Error --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Content Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Toolbar: Search --}}
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between gap-4 bg-gray-50/30">
                <form action="{{ route('klasifikasi.index') }}" method="GET" class="relative w-full md:w-96">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fa-solid fa-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama klasifikasi..." 
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </form>
                <div>
                    {{-- Optional filter or info --}}
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-24">No</th>
                            <th class="px-6 py-4 w-48">Kode</th>
                            <th class="px-6 py-4">Nama / Uraian Klasifikasi</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($klasifikasis as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 text-gray-500 font-medium text-sm">
                                {{ $klasifikasis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 font-mono font-bold px-2 py-1 rounded text-sm border border-blue-100">
                                    {{ $item->kode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium text-sm">
                                {{ $item->uraian ?? $item->nama }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 transition-opacity">
                                    <button onclick="editModal('{{ $item->id }}', '{{ $item->kode }}', `{{ $item->uraian }}`)"
                                        class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button onclick="deleteModal('{{ $item->id }}', '{{ $item->kode }}')"
                                        class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-4 text-gray-200"></i>
                                    <p class="text-lg font-medium text-gray-500">Data tidak ditemukan</p>
                                    <p class="text-sm">Silakan tambahkan klasifikasi baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $klasifikasis->withQueryString()->links() }}
            </div>
        </div>
    </main>
</div>

{{-- MODAL FORM (Create/Edit) --}}
<div id="klasifikasiModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4" id="modalTitle">Tambah Klasifikasi</h3>
            
            <form id="klasifikasiForm" method="POST" action="{{ route('klasifikasi.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="editId">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" id="inputKode" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono"
                            placeholder="Contoh: DL.01">
                    </div>
                    


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uraian / Keterangan</label>
                        <textarea name="uraian" id="inputUraian" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm transform transition-all p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600 text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Klasifikasi?</h3>
            <p class="text-sm text-gray-500 mb-6">Anda akan menghapus kode <span id="deleteKodeDisplay" class="font-bold text-gray-800"></span>. Tindakan ini tidak dapat dibatalkan.</p>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm shadow-sm">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Klasifikasi';
        document.getElementById('klasifikasiForm').action = "{{ route('klasifikasi.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('editId').value = '';
        document.getElementById('inputKode').value = '';
        document.getElementById('inputUraian').value = '';
        
        document.getElementById('klasifikasiModal').classList.remove('hidden');
    }

    function editModal(id, kode, uraian) {
        document.getElementById('modalTitle').textContent = 'Edit Klasifikasi';
        // Construct update URL manually since route helper is PHP
        document.getElementById('klasifikasiForm').action = "/admin/klasifikasi/" + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('editId').value = id;
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputUraian').value = uraian;
        
        document.getElementById('klasifikasiModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('klasifikasiModal').classList.add('hidden');
    }

    function deleteModal(id, kode) {
        document.getElementById('deleteKodeDisplay').textContent = kode;
        document.getElementById('deleteForm').action = "/admin/klasifikasi/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
