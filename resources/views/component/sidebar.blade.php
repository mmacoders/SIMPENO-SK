<aside class="fixed left-0 top-16 h-full w-64 bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 shadow-2xl">
    <nav class="p-4 space-y-2">

        {{-- Dashboard (Visible to All) --}}
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg
            {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
            transition-all duration-200 group">
            <i class="fa-solid fa-house mr-3"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        @if(auth()->user()->role !== 'admin')
        {{-- Tambah SK (User Only) --}}
        <a href="{{ route('sk.create') }}" class="flex items-center p-3 rounded-lg
            {{ request()->routeIs('sk.create') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
            transition-all duration-200 group">
            <i class="fa-solid fa-plus mr-3"></i>
            <span class="font-medium">Tambah SK</span>
        </a>

        {{-- Arsip SK (User Only) --}}
        <a href="{{ route('sk.archive') }}" class="flex items-center p-3 rounded-lg
            {{ request()->routeIs('sk.archive') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
            transition-all duration-200 group">
            <i class="fa-solid fa-box-archive mr-3"></i>
            <span class="font-semibold">Arsip SK</span>
        </a>

        {{-- Legalisir Online (User View) --}}
        <a href="{{ route('legalisir.index') }}" class="flex items-center p-3 rounded-lg
            {{ request()->routeIs('legalisir.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
            transition-all duration-200 group">
            <i class="fa-solid fa-file-signature mr-3"></i>
            <span class="font-medium">Legalisir Saya</span>
        </a>
        @endif

        {{-- Admin Menu --}}
        @if(auth()->user()->role === 'admin')
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">Administrator</p>
            </div>

            {{-- Legalisir Online (Admin View - Approval) --}}
            <a href="{{ route('legalisir.index') }}" class="flex items-center p-3 rounded-lg
                {{ request()->routeIs('legalisir.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
                transition-all duration-200 group">
                <i class="fa-solid fa-file-signature mr-3"></i>
                <span class="font-medium">Permohonan Legalisir</span>
            </a>

            <a href="{{ route('kategori-sks.index') }}" class="flex items-center p-3 rounded-lg
                {{ request()->routeIs('kategori-sks.index') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
                transition-all duration-200 group">
                <i class="fa-solid fa-tags mr-3"></i>
                <span class="font-medium">Jenis Surat</span>
            </a>

            <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg
                {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
                transition-all duration-200 group">
                <i class="fa-solid fa-users mr-3"></i>
                <span class="font-medium">Manage User</span>
            </a>
            
            <a href="{{ route('activity_logs.index') }}" class="flex items-center p-3 rounded-lg
                {{ request()->routeIs('activity_logs.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-l-4 border-blue-300 shadow-md' : 'text-blue-100 hover:bg-gradient-to-r hover:from-blue-600/70 hover:to-purple-600/70 hover:text-white' }}
                transition-all duration-200 group">
                <i class="fa-solid fa-clock-rotate-left mr-3"></i>
                <span class="font-medium">Riwayat Aktivitas</span>
            </a>
        @endif
    </nav>
</aside>