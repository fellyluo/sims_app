{{-- Satu blok dashboard yang bisa di-drag/sembunyikan. Butuh: $block, $spans, $hiddenBlocks, $blockLabel --}}
<div class="dash-block {{ $spans[$block] ?? 'col-span-12' }} {{ in_array($block, $hiddenBlocks) ? 'dash-hidden' : '' }}" data-block="{{ $block }}" :class="{ 'dash-hidden': hidden.includes('{{ $block }}') }">
    <span class="dash-handle"><i data-lucide="grip-vertical" class="w-3.5 h-3.5"></i> {{ $blockLabel[$block] ?? $block }}</span>
    <button type="button" class="dash-remove" @click.stop.prevent="toggleHide('{{ $block }}')"
            :title="hidden.includes('{{ $block }}') ? 'Tampilkan blok' : 'Sembunyikan blok'">
        <i data-lucide="x" class="w-3.5 h-3.5" x-show="!hidden.includes('{{ $block }}')"></i>
        <i data-lucide="plus" class="w-3.5 h-3.5" x-show="hidden.includes('{{ $block }}')" x-cloak></i>
    </button>
    <span class="dash-hidden-badge">Disembunyikan</span>
    @includeIf('dashboard.blocks.'.$block)
</div>
