<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-800">

    {{-- HEADER --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center gap-3">
            <a href="{{ route('dashboard') }}" wire:navigate class="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-none">Log Aktivitas</h1>
                <p class="text-xs text-gray-500 mt-1">Pantau semua tindakan pengguna.</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-6">
        
        {{-- FILTER --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm mb-6 space-y-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari aktivitas..." class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="flex gap-2">
                <select wire:model.live="userId" class="w-1/2 rounded-xl border-gray-200 text-xs py-2">
                    <option value="">Semua User</option>
                    @foreach($users as $u) <option value="{{ $u->id }}">{{ $u->name }}</option> @endforeach
                </select>
                <input wire:model.live="date" type="date" class="w-1/2 rounded-xl border-gray-200 text-xs py-2">
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="space-y-6">
            @php $lastDate = null; @endphp

            @forelse($logs as $log)
                @php
                    $currentDate = $log->created_at->format('Y-m-d');
                    $isNewDate = $currentDate != $lastDate;
                    $lastDate = $currentDate;
                    $props = $log->properties ?? [];
                    $color = $props['color'] ?? 'bg-gray-100 text-gray-600';
                    $icon = $props['icon'] ?? '📝';
                @endphp

                @if($isNewDate)
                    <div class="sticky top-[80px] z-20 pt-2 pb-2 bg-gray-50/95 backdrop-blur-sm">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest border-l-4 border-blue-500 pl-2">
                            {{ $log->created_at->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                @endif

                <div class="flex gap-4 relative">
                    {{-- Garis Timeline --}}
                    <div class="absolute left-[19px] top-8 bottom-[-24px] w-0.5 bg-gray-200"></div>

                    {{-- Avatar / Icon --}}
                    <div class="relative z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm shadow-sm border border-white ring-4 ring-gray-50 {{ $color }}">
                            {{ $icon }}
                        </div>
                    </div>

                    {{-- Content Card --}}
                    <div class="flex-1 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-1">
                            <div>
                                <p class="text-xs font-bold text-gray-900">
                                    {{ $log->user->name ?? 'Sistem / User Terhapus' }}
                                </p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wide">{{ $log->user->role ?? 'System' }}</p>
                            </div>
                            <span class="text-[10px] text-gray-400 font-mono bg-gray-100 px-2 py-1 rounded-lg">
                                {{ $log->created_at->format('H:i') }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-700 mt-2 font-medium leading-relaxed">
                            {!! preg_replace('/(Membuat|Mengubah|Menghapus)/', '<b>$1</b>', $log->description) !!}
                        </p>

                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-[10px] px-2 py-1 rounded bg-gray-100 text-gray-500 border border-gray-200">
                                {{ $log->subject_type }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 opacity-50">
                    <p class="text-sm text-gray-500">Belum ada aktivitas tercatat.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>