@extends('layouts.storefront')

@section('content')

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if ($packages->isEmpty() && $sweets->isEmpty())
            <p class="text-center py-20" style="color: var(--color-text); opacity: 0.7;">
                @if ($locale === 'ar')
                    لا توجد منتجات متاحة حاليًا.
                @else
                    No products available right now.
                @endif
            </p>
        @endif

        @if ($packages->isNotEmpty())
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-6" style="color: var(--color-accent-dark);">
                    @if ($locale === 'ar')
                        باقات الهدايا
                    @else
                        Gift Packages
                    @endif
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($packages as $product)
                        @include('storefront.partials.product-card', ['product' => $product, 'locale' => $locale])
                    @endforeach
                </div>
            </section>
        @endif

        @if ($sweets->isNotEmpty())
            <section>
                <h2 class="text-2xl font-semibold mb-6" style="color: var(--color-accent-dark);">
                    @if ($locale === 'ar')
                        الحلويات
                    @else
                        Sweets
                    @endif
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($sweets as $product)
                        @include('storefront.partials.product-card', ['product' => $product, 'locale' => $locale])
                    @endforeach
                </div>
            </section>
        @endif

    </div>

@endsection
