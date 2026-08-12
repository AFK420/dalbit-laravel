{{-- Expects $product and $locale to be passed in via @include(...) --}}
<article class="rounded-lg overflow-hidden border flex flex-col"
         style="border-color: var(--color-border); background-color: var(--color-surface);">

    <div class="aspect-square w-full flex items-center justify-center"
         style="background-color: var(--color-bg);">
        @if ($product->image_path)
            <img
                src="{{ Illuminate\Support\Facades\Storage::url($product->image_path) }}"
                alt="{{ $product->localizedName($locale) }}"
                class="w-full h-full object-cover"
                loading="lazy"
            >
        @else
            {{-- No image on file yet: show the fallback placeholder text/description
                 the admin entered, so the card never renders visually empty. --}}
            <span class="text-sm px-4 text-center" style="color: var(--color-accent-dark);">
                {{ $product->fallback_placeholder ?? $product->localizedName($locale) }}
            </span>
        @endif
    </div>

    <div class="p-4 flex flex-col flex-1">
        <h3 class="font-semibold text-lg" style="color: var(--color-text);">
            {{ $product->localizedName($locale) }}
        </h3>

        @if ($product->localizedShortDescription($locale))
            <p class="text-sm mt-1 flex-1" style="color: var(--color-text); opacity: 0.75;">
                {{ $product->localizedShortDescription($locale) }}
            </p>
        @endif

        <div class="mt-3 flex items-center justify-between">
            <span class="font-semibold" style="color: var(--color-accent-dark);">
                {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
            </span>
        </div>
    </div>
</article>
