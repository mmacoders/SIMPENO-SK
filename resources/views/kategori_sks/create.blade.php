@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 flex">

    @include('component.sidebar')

    <main class="flex-1 p-4 md:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-3 mb-6">
                 <a href="{{ route('kategori-sks.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                     <h2 class="text-2xl font-bold text-gray-800">Tambah Kategori Baru</h2>
                    <p class="text-sm text-gray-500">Buat jenis surat keputusan baru untuk sistem arsip.</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="animate-fade-in-down mb-6 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200 flex items-start shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl mr-3 mt-0.5"></i>
                <div>
                    <span class="font-medium block mb-1">Terjadi Kesalahan:</span>
                    <ul class="list-disc list-inside text-sm opacity-90">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                        Formulir Kategori
                    </h3>
                </div>

                <div class="p-6 md:p-8">
                    <form action="{{ route('kategori-sks.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Surat</label>
                                <div class="relative">
                                     <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fa-solid fa-tag"></i>
                                    </span>
                                    <input type="text" name="jenis_surat" value="{{ old('jenis_surat') }}"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400"
                                        placeholder="Misal: Surat Keputusan, Surat Tugas..." required>
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5 ml-1">Nama kategori surat yang akan ditampilkan di pilihan jenis.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Klasifikasi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fa-solid fa-hashtag"></i>
                                    </span>
                                    <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400 font-mono"
                                        placeholder="Contoh: 005">
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5 ml-1">Kode default yang akan muncul saat membuat SK jenis ini.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-50">
                            <a href="{{ route('kategori-sks.index') }}"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-save mr-1.5"></i> Simpan Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection