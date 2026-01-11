@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 flex">
    {{-- Sidebar --}}
    @include('component.sidebar')

    <main class="flex-1 p-4 md:p-8">
        @if(session('success'))
        <div class="animate-fade-in-down mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
             <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Manajemen Jenis Surat</h2>
                <p class="text-gray-500 mt-1">Atur kategori dan kode klasifikasi surat keputusan.</p>
            </div>
            
            <a href="{{ route('kategori-sks.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
             
             <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-blue-500"></i>
                    Daftar Kategori
                </h3>
             </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold w-20 text-center">#</th>
                            <th class="py-4 px-6 font-semibold">Jenis Surat</th>
                            <th class="py-4 px-6 font-semibold">Kode Klasifikasi</th>
                            <th class="py-4 px-6 font-semibold text-center min-w-[140px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kategoris as $i => $kategori)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-400 font-mono text-sm">{{ $kategoris->firstItem() + $i }}</td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-700">{{ $kategori->jenis_surat }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded text-xs font-mono font-semibold">
                                    {{ $kategori->kode_klasifikasi ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('kategori-sks.edit', $kategori->id) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors tooltip" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    <form action="{{ route('kategori-sks.destroy', $kategori->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors tooltip" title="Hapus">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-layer-group text-xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Belum ada kategori SK.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategoris->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                {{ $kategoris->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection