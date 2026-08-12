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

        <div class="mt-4 pt-4 border-t" style="border-color: var(--color-border);">
            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="100" class="w-16 border rounded-md px-2 text-center" style="border-color: var(--color-border); color: var(--color-text);">
                <button type="submit" class="flex-1 py-1.5 px-3 text-sm font-medium rounded-md transition-opacity hover:opacity-90" style="background-color: var(--color-accent-dark); color: var(--color-bg);">
                    {{ $locale === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}
                </button>
            </form>
        </div>
    </div>
</article>
