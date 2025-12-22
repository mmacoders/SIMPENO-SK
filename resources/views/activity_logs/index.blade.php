@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50/50">
    <!-- Sidebar -->
    @include('component.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Riwayat Aktivitas</h2>
                <p class="text-gray-500 mt-1">Pantau semua aktivitas pengguna dalam sistem secara real-time.</p>
            </div>
            
            <div class="flex items-center gap-2 text-sm text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                <i class="fa-regular fa-clock text-blue-500"></i>
                <span>Server Time: {{ now()->format('d M Y H:i') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                    Log Aktivitas Terbaru
                </h3>
             </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Waktu</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Pengguna</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Aksi</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Deskripsi</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-right">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-bold mb-0.5">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-400 font-mono flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i> {{ $log->created_at->format('H:i:s') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm border border-blue-50">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $log->user->name ?? 'Guest/Deleted' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClass = match($log->action) {
                                        'Login' => 'bg-green-50 text-green-700 border-green-100',
                                        'Logout' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Create SK' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Update SK' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'Delete SK' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-100'
                                    };
                                    
                                    $icon = match($log->action) {
                                        'Login' => 'fa-right-to-bracket',
                                        'Logout' => 'fa-right-from-bracket',
                                        'Create SK' => 'fa-plus',
                                        'Update SK' => 'fa-pen-to-square',
                                        'Delete SK' => 'fa-trash',
                                        default => 'fa-circle-info'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }} flex items-center w-fit gap-2">
                                    <i class="fa-solid {{ $icon }} text-[10px] opacity-70"></i>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-sm">
                                <p class="truncate group-hover:whitespace-normal transition-all duration-300" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-400 text-right">
                                <span class="bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                    {{ $log->ip_address }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                        <i class="fa-solid fa-clock-rotate-left text-3xl"></i>
                                    </div>
                                    <p class="font-medium text-lg text-gray-600">Belum ada aktivitas tercatat.</p>
                                    <p class="text-sm text-gray-400">Aktivitas pengguna akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
