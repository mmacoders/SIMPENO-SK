@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50/50 flex">

    <!-- Sidebar -->
    @include('component.sidebar')

    <main class="flex-1 p-4 md:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-3 mb-6">
                 <a href="{{ route('users.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                     <h2 class="text-2xl font-bold text-gray-800">Tambah User Baru</h2>
                    <p class="text-sm text-gray-500">Buat akun pengguna baru dalam aplikasi.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-user-plus text-blue-500"></i>
                         Formulir Pengguna
                    </h3>
                </div>

                <div class="p-6 md:p-8">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400"
                                        placeholder="Contoh: John Doe">
                                </div>
                                @error('name')<p class="text-xs text-red-500 mt-1 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <div class="relative">
                                     <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400"
                                        placeholder="nama@perusahaan.com">
                                </div>
                                @error('email')<p class="text-xs text-red-500 mt-1 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                    <div class="relative">
                                         <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" required
                                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400"
                                            placeholder="Minimal 8 karakter">
                                    </div>
                                    @error('password')<p class="text-xs text-red-500 mt-1 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                           <i class="fa-solid fa-lock"></i>
                                       </span>
                                        <input type="password" name="password_confirmation" required
                                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-gray-400"
                                            placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Role Pengguna</label>
                                <div class="relative">
                                     <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </span>
                                    <select name="role"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all appearance-none bg-white">
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Pengguna Biasa)</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                                    </select>
                                    <span class="absolute right-4 top-3 text-gray-400 pointer-events-none">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5 ml-1">Admin memiliki akses penuh ke sistem.</p>
                                @error('role')<p class="text-xs text-red-500 mt-1 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-50">
                            <a href="{{ route('users.index') }}"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-save mr-1.5"></i> Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection