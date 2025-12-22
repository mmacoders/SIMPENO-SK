@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    @include('component.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8 bg-gray-50/50">
        
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
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard {{ auth()->user()->role === 'admin' ? 'Administrator' : '' }}</h2>
                <p class="text-gray-500 mt-1">
                    @if(auth()->user()->role === 'admin')
                    Selamat datang kembali, Admin! Berikut ringkasan aktivitas sistem.
                    @else
                    Selamat datang, {{ auth()->user()->name }}! Kelola surat keputusan Anda.
                    @endif
                </p>
            </div>
            <div class="hidden md:block text-right">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Waktu Server</span>
                <p class="text-lg font-mono text-gray-700 font-bold">{{ now()->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
            <!-- Statistik Admin Card Range -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <!-- User Terdaftar -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                         <i class="fa-solid fa-users text-6xl text-blue-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</h3>
                    </div>
                </div>

                <!-- Total Arsip SK -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 p-4 opacity-10">
                         <i class="fa-solid fa-file-invoice text-6xl text-indigo-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Arsip SK</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $totalSK ?? 0 }}</h3>
                    </div>
                </div>
                
                 <!-- Permohonan Legalisir Pending -->
                 @php 
                    $pendingLegalisir = \App\Models\LegalisirRequest::where('status', 'pending')->count();
                 @endphp
                 <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 p-4 opacity-10">
                         <i class="fa-solid fa-file-signature text-6xl text-orange-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Legalisir Pending</p>
                        <h3 class="text-3xl font-bold text-gray-800 {{ $pendingLegalisir > 0 ? 'text-orange-600' : '' }}">{{ $pendingLegalisir }}</h3>
                    </div>
                </div>

                <!-- Aktivitas Hari Ini -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 p-4 opacity-10">
                         <i class="fa-solid fa-clock-rotate-left text-6xl text-emerald-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Aktivitas Hari Ini</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\ActivityLog::whereDate('created_at', today())->count() }}</h3>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->role === 'pimpinan')
            <!-- Statistik & Laporan Pimpinan -->
            <div class="mb-10 space-y-8">
                
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-6">
                        <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-2xl">
                             <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total SK Bulan Ini</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $skBulan ?? 0 }}</h3>
                            <p class="text-xs text-blue-600 mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
                        </div>
                     </div>
                     <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-6">
                        <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl">
                             <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total SK Tahun Ini</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $skTahun ?? 0 }}</h3>
                            <p class="text-xs text-indigo-600 mt-1">Periode Tahun {{ now()->year }}</p>
                        </div>
                     </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Laporan Tahunan (5 Tahun) Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-chart-column text-blue-500"></i> Statistik Tahunan (5 Thn Terakhir)
                            </h3>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                                    <tr>
                                        <th class="px-5 py-3">Tahun</th>
                                        <th class="px-5 py-3 text-center">Jumlah SK</th>
                                        <th class="px-5 py-3 text-right">Trend</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($laporan5Tahun as $l5t)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-5 py-3 font-bold text-gray-700">{{ $l5t->tahun }}</td>
                                        <td class="px-5 py-3 text-center font-semibold text-gray-800">{{ $l5t->total }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="w-24 h-2 bg-blue-100 rounded-full ml-auto overflow-hidden">
                                                {{-- Simulasi visualisasi bar sederhana --}}
                                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $totalSK > 0 ? ($l5t->total / $totalSK * 100) : 0 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-4 text-center text-gray-400">Belum ada data history.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Laporan Bulanan (Tahun Ini) Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-indigo-500"></i> Statistik Bulanan ({{ now()->year }})
                            </h3>
                        </div>
                         <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                                    <tr>
                                        <th class="px-5 py-3">Bulan</th>
                                        <th class="px-5 py-3 text-center">Jumlah SK</th>
                                        <th class="px-5 py-3 text-right">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($laporanBulananTahunIni as $lb)
                                    @php 
                                        $percent = $skTahun > 0 ? round(($lb->total / $skTahun) * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-5 py-3 font-medium">{{ \Carbon\Carbon::createFromDate(null, $lb->bulan, 1)->translatedFormat('F') }}</td>
                                        <td class="px-5 py-3 text-center font-bold text-gray-800">{{ $lb->total }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="text-xs text-gray-500">{{ $percent }}%</span>
                                                <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $percent }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-4 text-center text-gray-400">Belum ada data tahun ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Statistik User Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Card 1: Total SK Saya -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fa-solid fa-folder-open text-8xl text-blue-600 transform group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-file text-lg"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">SK Saya</span>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ \App\Models\SK::where('user_id', auth()->id())->count() }}</h3>
                        <p class="text-sm text-gray-400">Total SK yang Anda upload</p>
                    </div>
                </div>

                <!-- Card 2: SK Bulan Ini -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fa-solid fa-calendar-day text-8xl text-indigo-600 transform group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                           <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                               <i class="fa-regular fa-calendar text-lg"></i>
                           </div>
                           <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Bulan Ini</span>
                       </div>
                       <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ \App\Models\SK::where('user_id', auth()->id())->whereMonth('tanggal_ditetapkan', now()->month)->count() }}</h3>
                       <p class="text-sm text-gray-400">Diserahkan pada {{ now()->translatedFormat('F') }}</p>
                   </div>
               </div>
               
               <!-- Card 3: Legalisir Pending -->
               <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fa-solid fa-file-signature text-8xl text-purple-600 transform group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                           <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                               <i class="fa-solid fa-clock text-lg"></i>
                           </div>
                           <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Legalisir Pending</span>
                       </div>
                       <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ \App\Models\LegalisirRequest::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h3>
                       <p class="text-sm text-gray-400">Menunggu persetujuan admin</p>
                   </div>
               </div>
            </div>
        @endif
        
        <!-- Tabel SK Terbaru (Common) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
             <!-- Header Table -->
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                     <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                        Surat Keputusan Terbaru
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar 10 surat keputusan yang baru dibuat.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    {{-- Filter Component --}}
                    <div class="relative group">
                         <button id="toggleFilterBtn" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-200 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-100 flex items-center text-sm font-medium">
                            <i class="fa-solid fa-filter mr-2 text-gray-400"></i> Filter
                            <i class="fa-solid fa-chevron-down ml-2 text-xs text-gray-400"></i>
                        </button>
                        
                        {{-- Dropdown Filter Content --}}
                        <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-xl shadow-2xl z-50 p-5 animate-fade-in-up">
                            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-50">
                                <span class="font-semibold text-gray-800 text-sm">Filter Data</span>
                            </div>

                             <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Kata Kunci</label>
                                    <div class="relative">
                                         <span class="absolute left-3 top-2.5 text-gray-400 text-xs">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </span>
                                        <input id="dashSearch" type="text" placeholder="Cari Nomor, Perihal..."
                                            class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Spesifik</label>
                                    <input type="date" id="dashDate" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all">
                                </div>
    
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis Surat</label>
                                    <select id="dashJenis" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all">
                                        <option value="">Semua Jenis</option>
                                        @foreach($kategoriSK as $kat)
                                        <option value="{{ $kat->jenis_surat }}">{{ $kat->jenis_surat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-4 mt-2 gap-2">
                                <button id="resetFilterBtn" class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md font-medium transition-colors">Reset</button>
                                <button id="applyFilterBtn" class="bg-blue-600 text-white text-xs px-4 py-1.5 rounded-md hover:bg-blue-700 shadow-sm font-medium transition-all">Terapkan</button>
                            </div>
                        </div>
                    </div>

                    {{-- Export Excel --}}
                    <button type="button" id="btnExportExcelDashboard" data-url="{{ route('sk.export') }}"
                        class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 rounded-lg hover:bg-emerald-100 text-sm font-medium transition-all flex items-center">
                        <i class="fa-solid fa-file-excel mr-2"></i> Export
                    </button>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">Nomor & Klasifikasi</th>
                            <th class="py-4 px-6 font-semibold">Perihal</th>
                            <th class="py-4 px-6 font-semibold">Jenis</th>
                            <th class="py-4 px-6 font-semibold">Penandatangan</th>
                            <th class="py-4 px-6 font-semibold">Tanggal</th>
                            <th class="py-4 px-6 font-semibold text-center">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody id="dashTableBody" class="divide-y divide-gray-100">
                        @forelse($dataSK as $sk)
                        <tr class="hover:bg-blue-50/30 transition-colors dash-row group cursor-default"
                            data-year="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('Y') }}"
                            data-month="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('m') }}"
                            data-date="{{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->format('Y-m-d') }}"
                            data-keywords="{{ $sk->nomor_sk }} {{ $sk->kode_klasifikasi }} {{ $sk->perihal }} {{ $sk->jenis_surat }} {{ $sk->pejabat_penandatangan }}">
                            
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 text-sm">{{ $sk->nomor_sk }}</span>
                                    <span class="text-xs text-gray-500 font-mono mt-0.5 bg-gray-100 px-1.5 py-0.5 rounded w-fit">{{ $sk->kode_klasifikasi }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-sm text-gray-700 font-medium line-clamp-2 max-w-xs" title="{{ $sk->perihal }}">{{ $sk->perihal }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ $sk->jenis_surat }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $sk->pejabat_penandatangan }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-gray-500 font-medium">
                                    {{ \Carbon\Carbon::parse($sk->tanggal_ditetapkan)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(!empty($sk->file_pdf))
                                <a href="{{ route('sk.view', $sk->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors tooltip" title="Lihat Dokumen">
                                    <i class="fa-solid fa-file"></i>
                                </a>
                                @else
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed" title="Tidak ada file">
                                    <i class="fa-solid fa-file"></i>
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-regular fa-folder-open text-2xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Belum ada data Surat Keputusan.</p>
                                    <a href="{{ route('sk.create') }}" class="mt-3 text-sm text-blue-600 hover:underline">Buat SK Baru</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pesan jika filter/search tidak ada hasil --}}
            <div id="dashNoResult" class="hidden text-center text-gray-500 py-12">
                 <div class="flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-3xl mb-3 opacity-50"></i>
                    <p>Tidak ada SK yang cocok dengan filter / pencarian.</p>
                </div>
            </div>

            {{-- Pagination --}}
            @if($dataSK instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                {{ $dataSK->links() }}
            </div>
            @endif
        </div>
    </div>


<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4">Edit Surat Keputusan</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('sk.update') }}">
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" name="judul" id="edit_judul" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Perihal</label>
                <select name="kategori" id="edit_kategori" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="Kepegawaian">SK</option>
                    <option value="Keuangan">BAST</option>
                    <option value="Akademik">Legalisir</option>
                    <option value="Umum">Sertifikat</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Tanggal Ditetapkan *</label>
                <input type="date" name="tanggal_ditetapkan" id="edit_tanggal" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Pejabat Penandatangan *</label>
                <input type="text" name="pejabat_penandatangan" id="edit_pejabat" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Upload File PDF (Opsional)</label>
                <input type="file" name="file_pdf" accept="application/pdf"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Maksimal 2MB, format PDF</p>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="btnCloseModal"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>
</div>
</main>
</div>

<script>
// fungsi filter/search dashboard
// fungsi filter/search dashboard
function applyDashFilters() {
    const searchInput = document.getElementById('dashSearch');
    const dateInput = document.getElementById('dashDate');
    const jenisSelect = document.getElementById('dashJenis');
    const noResultEl = document.getElementById('dashNoResult');
    const filterDropdown = document.getElementById('filterDropdown');

    const q = (searchInput?.value || '').toLowerCase().trim();
    const dateValue = dateInput?.value || '';
    const jenisValue = (jenisSelect?.value || '').toLowerCase();   

    const rows = document.querySelectorAll('.dash-row');
    let anyVisible = false;

    rows.forEach(row => {
        // Ambil data dari atribut row. Pastikan format tanggal di dataset sesuai
        // Kita perlu data tanggal lengkap YYYY-MM-DD
        const rowDate = row.dataset.date; // Perlu update di HTML row agar ada data-date="YYYY-MM-DD"
        const rowKeywords = (row.dataset.keywords || '').toLowerCase();
        
        // Ambil jenis surat dari keywords atau kolom tabel (asumsi ada di keywords)
        
        let visible = true;

        // filter text
        if (q && !rowKeywords.includes(q)) {
            visible = false;
        }

        // filter tanggal (exact match)
        if (dateValue && rowDate !== dateValue) {
            visible = false;
        }

        // filter jenis surat
        if (jenisValue && !rowKeywords.includes(jenisValue)) {
             visible = false;
        }

        row.style.display = visible ? '' : 'none';
        if (visible) anyVisible = true;
    });

    if (noResultEl) {
        if (anyVisible) {
            noResultEl.classList.add('hidden');
        } else {
            noResultEl.classList.remove('hidden');
        }
    }
    
    // Tutup dropdown setelah apply (opsional)
    if(filterDropdown) filterDropdown.classList.add('hidden');
}

// Modal edit + export + filter dropdown
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Filter Dropdown
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    const applyFilterBtn = document.getElementById('applyFilterBtn');
    const resetFilterBtn = document.getElementById('resetFilterBtn');

    if(toggleFilterBtn && filterDropdown) {
        toggleFilterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
        });

        // Klik di luar dropdown menutup dropdown
        document.addEventListener('click', function(e) {
            if (!filterDropdown.contains(e.target) && !toggleFilterBtn.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });
        
        // Mencegah dropdown tertutup saat diklik di dalamnya
        filterDropdown.addEventListener('click', function(e){
            e.stopPropagation();
        });
    }

    // Apply Filter
    if(applyFilterBtn) {
        applyFilterBtn.addEventListener('click', applyDashFilters);
    }

    // Reset Filter
    if(resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            document.getElementById('dashSearch').value = '';
            document.getElementById('dashDate').value = '';
            document.getElementById('dashJenis').value = '';
            applyDashFilters(); // Apply filter kosong (show all)
        });
    }

    // --- Kode Lama untuk Fitur Lain ---
    
    const editButtons = document.querySelectorAll('.btn-edit');
    const editModal = document.getElementById('editModal');
    const btnCloseModal = document.getElementById('btnCloseModal');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            // ... setup nilai form edit ...
            editModal.classList.remove('hidden');
        });
    });

    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', function() {
            editModal.classList.add('hidden');
        });
        editModal.addEventListener('click', function(e) {
            if (e.target === this) editModal.classList.add('hidden');
        });
    }

    // Export Excel (Dashboard)
    const btnExportDash = document.getElementById('btnExportExcelDashboard');
    if (btnExportDash) {
        btnExportDash.addEventListener('click', function() {
            const baseUrl = this.getAttribute('data-url');
            // Ambil nilai dari input baru kita
            const q = document.getElementById('dashSearch')?.value || '';
            const date = document.getElementById('dashDate')?.value || ''; 
            // Untuk export excel mungkin controller butuh thn/bln, atau kita kirim date full
            // Kita sesuaikan controller nanti, atau kirim sebagai param 'date'
            
            // Mengambil tahun dan bulan dari date input jika ada
            let year = '';
            let month = '';
            
            if(date) {
                const d = new Date(date);
                year = d.getFullYear();
                month = (d.getMonth() + 1).toString().padStart(2, '0');
            }

            const params = new URLSearchParams();
            if (year) params.set('year', year);
            if (month) params.set('month', month); 
            if (q) params.set('q', q);

            const url = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
            window.location.href = url;
        });
    }
});
</script>
@endsection