@extends('layouts.storefront')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold mb-8" style="color: var(--color-accent-dark);">
        {{ $locale === 'ar' ? 'إتمام الطلب' : 'Checkout' }}
    </h1>

    @if($errors->any())
        <div class="mb-8 p-4 rounded-md bg-red-50 border border-red-200 text-red-600">
            <ul class="list-disc px-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Order Summary --}}
    <div class="mb-8 rounded-lg shadow-sm border p-6" style="background-color: var(--color-surface); border-color: var(--color-border);">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: var(--color-text); border-color: var(--color-border);">
            {{ $locale === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
        </h2>
        <div class="divide-y mb-4" style="border-color: var(--color-border);">
            @foreach($items as $item)
                <div class="py-3 flex justify-between items-center text-sm">
                    <div>
                        <span class="font-medium" style="color: var(--color-text);">{{ $item['product']->localizedName($locale) }}</span>
                        <span class="text-xs ml-2 rtl:mr-2 opacity-75" style="color: var(--color-text);">({{ $item['quantity'] }} × {{ number_format((float) $item['product']->price, 2) }} {{ $item['product']->currency }})</span>
                    </div>
                    <div class="font-semibold" style="color: var(--color-text);">
                        {{ number_format((float) $item['subtotal'], 2) }} JOD
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between items-center pt-2 border-t font-bold text-lg" style="color: var(--color-accent-dark); border-color: var(--color-border);">
            <span>{{ $locale === 'ar' ? 'الإجمالي:' : 'Total:' }}</span>
            <span>{{ number_format((float) $total, 2) }} JOD</span>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" class="rounded-lg shadow-sm border p-6 sm:p-8" style="background-color: var(--color-surface); border-color: var(--color-border);">
        @csrf
        
        {{-- Honeypot field (hidden from users, but bots will fill it) --}}
        <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
            <label for="website">Website</label>
            <input
                id="website"
                name="website"
                type="text"
                tabindex="-1"
                autocomplete="off"
                value="{{ old('website') }}"
            >
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="customer_name" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'الاسم' : 'Name' }} <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">
            </div>

            <div>
                <label for="phone" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'رقم الهاتف' : 'Phone Number' }} <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="079XXXXXXX" class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);" pattern="^(?:07[789][0-9]{7}|\+9627[789][0-9]{7})$" title="Valid Jordanian mobile number (e.g., 0791234567 or +962791234567)">
            </div>

            <div>
                <label for="location" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'العنوان' : 'Delivery Address' }} <span class="text-red-500">*</span></label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" required class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="delivery_date" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'تاريخ التوصيل' : 'Delivery Date' }} <span class="text-red-500">*</span></label>
                    <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $earliestDate) }}" min="{{ $earliestDate }}" required class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">
                </div>

                <div>
                    <label for="delivery_slot" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'وقت التوصيل' : 'Delivery Slot' }} <span class="text-red-500">*</span></label>
                    <select name="delivery_slot" id="delivery_slot" required class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">
                        <option value="" disabled {{ old('delivery_slot') ? '' : 'selected' }}>{{ $locale === 'ar' ? 'اختر الوقت...' : 'Select slot...' }}</option>
                        <option value="9-12" {{ old('delivery_slot') === '9-12' ? 'selected' : '' }}>9:00 AM - 12:00 PM</option>
                        <option value="12-15" {{ old('delivery_slot') === '12-15' ? 'selected' : '' }}>12:00 PM - 3:00 PM</option>
                        <option value="15-18" {{ old('delivery_slot') === '15-18' ? 'selected' : '' }}>3:00 PM - 6:00 PM</option>
                        <option value="18-21" {{ old('delivery_slot') === '18-21' ? 'selected' : '' }}>6:00 PM - 9:00 PM</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="gift_note" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'رسالة الهدية (اختياري)' : 'Gift Note (Optional)' }}</label>
                <textarea name="gift_note" id="gift_note" rows="2" class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">{{ old('gift_note') }}</textarea>
            </div>

            <div>
                <label for="special_instructions" class="block font-medium mb-1" style="color: var(--color-text);">{{ $locale === 'ar' ? 'ملاحظات إضافية (اختياري)' : 'Special Instructions (Optional)' }}</label>
                <textarea name="special_instructions" id="special_instructions" rows="2" class="w-full border rounded-md px-4 py-2" style="border-color: var(--color-border); color: var(--color-text);">{{ old('special_instructions') }}</textarea>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end" style="border-color: var(--color-border);">
            <button type="submit" class="px-8 py-3 rounded-md font-semibold transition-opacity hover:opacity-90" style="background-color: var(--color-accent-dark); color: var(--color-bg);">
                {{ $locale === 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}
            </button>
        </div>
    </form>
</div>
@endsection
