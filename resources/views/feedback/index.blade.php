@extends('layouts.storefront')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div
        class="rounded-lg border p-6 sm:p-8"
        style="background-color: var(--color-surface); border-color: var(--color-border);"
    >
        <h1
            class="text-3xl font-bold mb-3"
            style="color: var(--color-accent-dark);"
        >
            {{ $locale === 'ar' ? 'شاركنا رأيك' : 'Share Your Feedback' }}
        </h1>

        <p class="mb-8" style="color: var(--color-text);">
            {{ $locale === 'ar'
                ? 'كيف كانت تجربتك معنا؟'
                : 'How was your experience with us?' }}
        </p>

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc px-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedback.store', $order) }}" method="POST">
            @csrf

            <fieldset>
                <legend
                    class="mb-4 font-semibold"
                    style="color: var(--color-text);"
                >
                    {{ $locale === 'ar' ? 'التقييم' : 'Rating' }}
                </legend>

                <div class="grid grid-cols-5 gap-2">
                    @for ($rating = 1; $rating <= 5; $rating++)
                        <label class="cursor-pointer text-center">
                            <input
                                type="radio"
                                name="rating"
                                value="{{ $rating }}"
                                class="sr-only peer"
                                {{ old('rating') == $rating ? 'checked' : '' }}
                                required
                            >

                            <span
                                class="block rounded-md border px-2 py-3 peer-checked:border-transparent peer-checked:ring-2"
                                style="border-color: var(--color-border); color: var(--color-text);"
                            >
                                {{ $rating }} ★
                            </span>
                        </label>
                    @endfor
                </div>
            </fieldset>

            <div class="mt-6">
                <label
                    for="comments"
                    class="mb-1 block font-medium"
                    style="color: var(--color-text);"
                >
                    {{ $locale === 'ar'
                        ? 'ملاحظاتك (اختياري)'
                        : 'Comments (Optional)' }}
                </label>

                <textarea
                    id="comments"
                    name="comments"
                    rows="5"
                    maxlength="2000"
                    class="w-full rounded-md border px-4 py-2"
                    style="border-color: var(--color-border); color: var(--color-text);"
                >{{ old('comments') }}</textarea>
            </div>

            <button
                type="submit"
                class="mt-6 w-full rounded-md px-6 py-3 font-semibold"
                style="background-color: var(--color-accent-dark); color: var(--color-bg);"
            >
                {{ $locale === 'ar' ? 'إرسال التقييم' : 'Submit Feedback' }}
            </button>
        </form>
    </div>
</div>
@endsection
