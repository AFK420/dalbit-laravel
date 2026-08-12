@extends('layouts.storefront')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="mb-6 inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <h1 class="text-3xl font-bold mb-4" style="color: var(--color-accent-dark);">
        {{ $locale === 'ar' ? 'تم استلام طلبك بنجاح!' : 'Order Received Successfully!' }}
    </h1>

    <p class="text-lg mb-8" style="color: var(--color-text);">
        {{ $locale === 'ar' ? 'شكراً لطلبك. سنقوم بالتواصل معك قريباً لتأكيد الطلب.' : 'Thank you for your order. We will contact you soon to confirm.' }}
    </p>

    <div class="bg-white rounded-lg shadow-sm border p-6 mb-8 text-left rtl:text-right" style="background-color: var(--color-surface); border-color: var(--color-border);">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2" style="color: var(--color-text); border-color: var(--color-border);">
            {{ $locale === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
        </h2>
        
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div style="color: var(--color-text); opacity: 0.8;">{{ $locale === 'ar' ? 'رقم الطلب:' : 'Order ID:' }}</div>
            <div class="font-mono" style="color: var(--color-text);">{{ substr($order->id, 0, 8) }}...</div>
            
            <div style="color: var(--color-text); opacity: 0.8;">{{ $locale === 'ar' ? 'الاسم:' : 'Name:' }}</div>
            <div style="color: var(--color-text);">{{ $order->customer_name }}</div>
            
            <div style="color: var(--color-text); opacity: 0.8;">{{ $locale === 'ar' ? 'تاريخ التوصيل:' : 'Delivery Date:' }}</div>
            <div style="color: var(--color-text);">{{ $order->delivery_date->format('Y-m-d') }} ({{ $order->delivery_slot }})</div>
            
            <div style="color: var(--color-text); opacity: 0.8;">{{ $locale === 'ar' ? 'المبلغ الإجمالي:' : 'Total Amount:' }}</div>
            <div class="font-bold" style="color: var(--color-accent-dark);">{{ number_format($order->total_amount, 2) }} JOD</div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a
            href="{{ route('feedback.create', $order) }}"
            class="inline-block rounded-md px-8 py-3 font-semibold transition-opacity hover:opacity-90"
            style="background-color: var(--color-accent-dark); color: var(--color-bg);"
        >
            {{ $locale === 'ar' ? 'شاركنا رأيك' : 'Leave Feedback' }}
        </a>
        <a
            href="{{ route('storefront.index') }}"
            class="inline-block rounded-md px-8 py-3 font-semibold border transition-opacity hover:opacity-90"
            style="border-color: var(--color-border); color: var(--color-text);"
        >
            {{ $locale === 'ar' ? 'العودة للصفحة الرئيسية' : 'Return to Home' }}
        </a>
    </div>
</div>
@endsection
