@props(['subtitle' => null])

<div class="text-center">
    <div class="flex justify-center">
        {{-- Inline SVG logo --}}
        <svg width="260" height="64" viewBox="0 0 520 128" fill="none" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="92" font-family="Inter, system-ui" font-size="88" font-weight="800" letter-spacing="2" fill="#101e23">NASS</text>
            <text x="270" y="92" font-family="Inter, system-ui" font-size="88" font-weight="800" letter-spacing="2" fill="#41778b">SPLIT</text>
            <line x1="245" y1="120" x2="330" y2="10" stroke="#5295ad" stroke-width="6" />
            <circle cx="324" cy="30" r="16" fill="#5295ad" />
        </svg>
    </div>

    @if($subtitle)
        <p class="mt-2 text-sm text-cerulean-800">{{ $subtitle }}</p>
    @endif
</div>
