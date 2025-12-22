@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50/50 flex">

    <!-- Sidebar -->
    @include('component.sidebar')

    <main class="flex-1 p-4 md:p-8">
        @if(session('success'))
        <div class="animate-fade-in-down mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="animate-fade-in-down mb-6 bg-red-50 text-red-800 p-4 rounded-xl border border-red-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
             <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Manajemen Pengguna</h2>
                <p class="text-gray-500 mt-1">Kelola akun dan hak akses pengguna aplikasi.</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-user-plus"></i> Tambah User
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
             <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-users text-blue-500"></i>
                    Daftar Pengguna
                </h3>
             </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">Nama Pengguna</th>
                            <th class="py-4 px-6 font-semibold">Email</th>
                            <th class="py-4 px-6 font-semibold">Role</th>
                            <th class="py-4 px-6 font-semibold text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border
                                    {{ $user->role === 'admin' 
                                        ? 'bg-purple-50 text-purple-700 border-purple-100' 
                                        : 'bg-green-50 text-green-700 border-green-100' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors tooltip" 
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors tooltip" 
                                            title="Hapus">
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
                                        <i class="fa-solid fa-users-slash text-xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Belum ada user terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection