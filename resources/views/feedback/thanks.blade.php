@extends('layouts.storefront')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <h1
        class="text-3xl font-bold mb-4"
        style="color: var(--color-accent-dark);"
    >
        {{ $locale === 'ar' ? 'شكرًا لملاحظاتك!' : 'Thank you for your feedback!' }}
    </h1>

    <p class="mb-8" style="color: var(--color-text);">
        {{ $locale === 'ar'
            ? 'نقدّر وقتك وملاحظاتك.'
            : 'We appreciate your time and feedback.' }}
    </p>

    <a
        href="{{ route('storefront.index') }}"
        class="inline-block rounded-md px-8 py-3 font-semibold"
        style="background-color: var(--color-accent-dark); color: var(--color-bg);"
    >
        {{ $locale === 'ar' ? 'العودة للرئيسية' : 'Return Home' }}
    </a>
</div>
@endsection
