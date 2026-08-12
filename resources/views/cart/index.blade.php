@extends('layouts.storefront')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold mb-8" style="color: var(--color-accent-dark);">
        {{ session('locale', config('app.locale')) === 'ar' ? 'سلة المشتريات' : 'Shopping Cart' }}
    </h1>

    @if (empty($items))
        <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100" style="background-color: var(--color-surface); border-color: var(--color-border);">
            <p class="text-lg text-gray-500 mb-4" style="color: var(--color-text);">
                {{ session('locale', config('app.locale')) === 'ar' ? 'سلة المشتريات فارغة.' : 'Your cart is empty.' }}
            </p>
            <a href="{{ route('storefront.index') }}" class="inline-block px-6 py-3 rounded-md font-semibold text-white transition-opacity hover:opacity-90" style="background-color: var(--color-accent-dark); color: var(--color-bg);">
                {{ session('locale', config('app.locale')) === 'ar' ? 'العودة للتسوق' : 'Continue Shopping' }}
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="rounded-lg shadow-sm border overflow-hidden" style="background-color: var(--color-surface); border-color: var(--color-border);">
                    <ul class="divide-y" style="border-color: var(--color-border);">
                        @foreach ($items as $item)
                            <li class="p-6 flex flex-col sm:flex-row items-center gap-6">
                                <div class="w-24 h-24 flex-shrink-0 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center" style="background-color: var(--color-bg);">
                                    @if ($item['product']->image_path)
                                        <img src="{{ Illuminate\Support\Facades\Storage::url($item['product']->image_path) }}" alt="{{ $item['product']->localizedName(session('locale', config('app.locale'))) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs text-center px-2" style="color: var(--color-accent-dark);">{{ $item['product']->fallback_placeholder ?? $item['product']->localizedName(session('locale', config('app.locale'))) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 text-center sm:text-left rtl:sm:text-right">
                                    <h3 class="text-lg font-semibold" style="color: var(--color-text);">{{ $item['product']->localizedName(session('locale', config('app.locale'))) }}</h3>
                                    <p class="text-sm mt-1" style="color: var(--color-accent-dark);">{{ number_format((float) $item['product']->price, 2) }} {{ $item['product']->currency }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="100" class="w-16 text-center border rounded-md py-1" style="border-color: var(--color-border); color: var(--color-text);">
                                        <button type="submit" class="text-sm underline" style="color: var(--color-accent);">{{ session('locale', config('app.locale')) === 'ar' ? 'تحديث' : 'Update' }}</button>
                                    </form>
                                    <form action="{{ route('cart.destroy', $item['product']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm underline text-red-500 hover:text-red-700">{{ session('locale', config('app.locale')) === 'ar' ? 'حذف' : 'Remove' }}</button>
                                    </form>
                                </div>
                                <div class="text-lg font-semibold w-24 text-right rtl:text-left" style="color: var(--color-text);">
                                    {{ number_format((float) $item['subtotal'], 2) }} {{ $item['product']->currency }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="lg:w-80">
                <div class="rounded-lg shadow-sm border p-6 sticky top-6" style="background-color: var(--color-surface); border-color: var(--color-border);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-text);">
                        {{ session('locale', config('app.locale')) === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                    </h3>
                    <div class="flex justify-between items-center text-xl font-bold mb-6" style="color: var(--color-accent-dark);">
                        <span>{{ session('locale', config('app.locale')) === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                        <span>{{ number_format((float) $total, 2) }} JOD</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="block w-full text-center py-3 rounded-md font-semibold transition-opacity hover:opacity-90" style="background-color: var(--color-accent-dark); color: var(--color-bg);">
                        {{ session('locale', config('app.locale')) === 'ar' ? 'إتمام الطلب' : 'Proceed to Checkout' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
