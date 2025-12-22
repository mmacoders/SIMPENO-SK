@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 flex">

    @include('component.sidebar')

    <main class="flex-1 p-4 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
             <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Permohonan Legalisir</h2>
                <p class="text-gray-500 mt-1">Kelola pengajuan legalisir dokumen dari pengguna.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="animate-fade-in-down mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
             <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-blue-500"></i>
                    Daftar Pengajuan
                </h3>
             </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">Tanggal</th>
                            <th class="py-4 px-6 font-semibold">Pemohon</th>
                            <th class="py-4 px-6 font-semibold">Dokumen</th>
                            <th class="py-4 px-6 font-semibold">Keperluan</th>
                            <th class="py-4 px-6 font-semibold text-center">Status</th>
                            <th class="py-4 px-6 font-semibold text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $req)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-6 text-sm">
                                <span class="font-medium text-gray-800 block">{{ $req->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400 text-xs">{{ $req->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $req->user->name }}</p>
                                        <p class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fa-brands fa-whatsapp text-green-500"></i> {{ $req->no_wa }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm">
                                    <p class="font-semibold text-blue-600 hover:text-blue-800 cursor-pointer">
                                        {{ $req->sk->nomor_sk }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]">{{ $req->sk->perihal }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                {{ $req->keperluan }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $statusClass = match($req->status) {
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'approved' => 'bg-green-50 text-green-700 border-green-100',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-100',
                                    };
                                    $icon = match($req->status) {
                                        'pending' => 'fa-clock',
                                        'approved' => 'fa-check-circle',
                                        'rejected' => 'fa-circle-xmark',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }} inline-flex items-center gap-1.5">
                                    <i class="fa-solid {{ $icon }}"></i>
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(auth()->user()->role === 'pimpinan' && $req->status === 'pending')
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Approve -->
                                    <button onclick="openActionModal('{{ $req->id }}', 'approve')" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700 transition-colors tooltip" 
                                        title="Setujui">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    
                                    <!-- Tombol Reject -->
                                    <button onclick="openActionModal('{{ $req->id }}', 'reject')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors tooltip" 
                                        title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                @elseif($req->catatan_admin)
                                <span class="text-gray-400 text-xs italic block mb-1">
                                    {{ $req->status == 'approved' ? 'Telah disetujui' : 'Ditolak' }}
                                </span>
                                <button class="text-xs flex items-center gap-1 mx-auto text-blue-500 hover:underline" title="{{ $req->catatan_admin }}">
                                    <i class="fa-solid fa-message"></i> Lihat Pesan
                                </button>
                                @else
                                <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-file-circle-question text-2xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Belum ada permohonan legalisir.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </main>

    <!-- Modal Action (Approve/Reject) -->
    <div id="actionModal" class="fixed inset-0 z-[9999] hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeActionModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all p-6">
                <h3 id="modalTitle" class="text-xl font-bold mb-2">Konfirmasi</h3>
                <p id="modalDesc" class="text-gray-600 mb-4 text-sm">Apakah Anda yakin?</p>

                <form id="actionForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <input type="hidden" name="status" id="inputStatus">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Optional)</label>
                        <textarea name="catatan_admin" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan untuk pemohon..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeActionModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">Batal</button>
                        <button type="submit" id="modalSubmitBtn" class="px-4 py-2 text-white rounded-lg text-sm font-medium shadow-sm transition-all"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function openActionModal(id, action) {
        const form = document.getElementById('actionForm');
        form.action = `/legalisir/${id}/status`;
        
        document.getElementById('inputStatus').value = action === 'approve' ? 'approved' : 'rejected';
        
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDesc');
        const btn = document.getElementById('modalSubmitBtn');
        const modal = document.getElementById('actionModal');

        if (action === 'approve') {
            title.innerText = 'Setujui Permohonan';
            title.className = 'text-xl font-bold mb-2 text-green-600';
            desc.innerText = 'Sistem akan mengirimkan notifikasi persetujuan via WhatsApp ke pemohon.';
            btn.innerText = 'Setujui & Kirim WA';
            btn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium shadow-sm transition-all';
        } else {
            title.innerText = 'Tolak Permohonan';
            title.className = 'text-xl font-bold mb-2 text-red-600';
            desc.innerText = 'Mohon berikan alasan penolakan pada kolom catatan.';
            btn.innerText = 'Tolak Permohonan';
            btn.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium shadow-sm transition-all';
        }

        modal.classList.remove('hidden');
    }

    function closeActionModal() {
        document.getElementById('actionModal').classList.add('hidden');
    }
</script>
@endsection
