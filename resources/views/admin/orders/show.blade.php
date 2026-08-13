@extends('layouts.storefront')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center text-sm font-medium hover:underline mb-2"
                style="color: var(--color-accent-dark);"
            >
                &larr; Back to Order Dashboard
            </a>
            <h1 class="text-3xl font-bold" style="color: var(--color-accent-dark);">
                Order Details
            </h1>
            <p class="text-xs text-gray-500 font-mono mt-1">
                ID: {{ $order->id }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <form
                method="POST"
                action="{{ route('admin.orders.status', $order) }}"
                class="flex items-center gap-2"
            >
                @csrf
                @method('PATCH')

                <label for="status-select" class="text-sm font-medium" style="color: var(--color-text);">
                    Status:
                </label>
                <select
                    id="status-select"
                    name="status"
                    class="rounded-md border px-3 py-1.5 text-sm font-semibold"
                    onchange="this.form.submit()"
                >
                    @foreach ($statuses as $option)
                        <option
                            value="{{ $option->value }}"
                            @selected($option === $order->status)
                        >
                            {{ str_replace('_', ' ', ucfirst($option->value)) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Customer & Delivery Info --}}
        <div
            class="md:col-span-2 rounded-lg border p-6 space-y-6"
            style="background-color: var(--color-surface); border-color: var(--color-border);"
        >
            <div>
                <h2 class="text-xl font-bold mb-4 border-b pb-2" style="color: var(--color-accent-dark); border-color: var(--color-border);">
                    Customer Details
                </h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Customer Name</dt>
                        <dd class="font-semibold text-base" style="color: var(--color-text);">
                            {{ $order->customer_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Phone Number</dt>
                        <dd class="font-semibold text-base" style="color: var(--color-text);">
                            <a href="tel:{{ $order->phone }}" class="hover:underline">
                                {{ $order->phone }}
                            </a>
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="font-medium text-gray-500">Delivery Address / Location</dt>
                        <dd class="font-medium text-base mt-0.5" style="color: var(--color-text);">
                            {{ $order->location }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Delivery Date</dt>
                        <dd class="font-semibold text-base" style="color: var(--color-text);">
                            {{ $order->delivery_date->format('Y-m-d') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Delivery Time Slot</dt>
                        <dd class="font-semibold text-base" style="color: var(--color-text);">
                            {{ $order->delivery_slot }}
                        </dd>
                    </div>
                </dl>
            </div>

            @if ($order->gift_note || $order->special_instructions || $order->deletion_reason)
                <div class="border-t pt-4" style="border-color: var(--color-border);">
                    <h3 class="text-md font-bold mb-3" style="color: var(--color-accent-dark);">
                        Notes & Instructions
                    </h3>

                    @if ($order->gift_note)
                        <div class="mb-3">
                            <span class="block text-xs font-semibold uppercase text-gray-500">Gift Note:</span>
                            <p class="text-sm italic rounded bg-amber-50/50 p-2.5 border border-amber-200 mt-1">
                                "{{ $order->gift_note }}"
                            </p>
                        </div>
                    @endif

                    @if ($order->special_instructions)
                        <div class="mb-3">
                            <span class="block text-xs font-semibold uppercase text-gray-500">Special Instructions:</span>
                            <p class="text-sm rounded bg-gray-50 p-2.5 border border-gray-200 mt-1">
                                {{ $order->special_instructions }}
                            </p>
                        </div>
                    @endif

                    @if ($order->deletion_reason)
                        <div class="mb-3">
                            <span class="block text-xs font-semibold uppercase text-red-600">Deletion / Cancellation Reason:</span>
                            <p class="text-sm rounded bg-red-50 p-2.5 border border-red-200 text-red-800 mt-1">
                                {{ $order->deletion_reason }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Items Table --}}
            <div class="border-t pt-4" style="border-color: var(--color-border);">
                <h3 class="text-md font-bold mb-3" style="color: var(--color-accent-dark);">
                    Ordered Items
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b" style="border-color: var(--color-border);">
                                <th class="py-2 font-semibold">Item</th>
                                <th class="py-2 font-semibold text-center">Qty</th>
                                <th class="py-2 font-semibold text-right">Price</th>
                                <th class="py-2 font-semibold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: var(--color-border);">
                            @foreach (($order->items ?? []) as $item)
                                <tr>
                                    <td class="py-2.5 font-medium">
                                        {{ $item['name'] ?? $item['title'] ?? 'Product' }}
                                    </td>
                                    <td class="py-2.5 text-center">
                                        {{ $item['quantity'] ?? 1 }}
                                    </td>
                                    <td class="py-2.5 text-right">
                                        {{ number_format((float) ($item['price'] ?? 0), 2) }} JOD
                                    </td>
                                    <td class="py-2.5 text-right font-semibold">
                                        {{ number_format((float) (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 2) }} JOD
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t font-bold text-base" style="border-color: var(--color-border);">
                                <td colspan="3" class="py-3 text-right">Total Amount:</td>
                                <td class="py-3 text-right" style="color: var(--color-accent-dark);">
                                    {{ number_format((float) $order->total_amount, 2) }} JOD
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar: Handling & Audit --}}
        <div class="space-y-6">
            <div
                class="rounded-lg border p-6"
                style="background-color: var(--color-surface); border-color: var(--color-border);"
            >
                <h3 class="text-md font-bold mb-4 border-b pb-2" style="color: var(--color-accent-dark); border-color: var(--color-border);">
                    Handling Audit
                </h3>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Handled By Admin</dt>
                        <dd class="font-semibold" style="color: var(--color-text);">
                            {{ $order->handledByAdmin?->name ?? 'Unassigned' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Handled At</dt>
                        <dd class="font-semibold" style="color: var(--color-text);">
                            {{ $order->handled_at ? $order->handled_at->format('Y-m-d H:i:s') : 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Order Created At</dt>
                        <dd class="font-semibold" style="color: var(--color-text);">
                            {{ $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-500">Customer IP</dt>
                        <dd class="font-mono text-xs text-gray-600">
                            {{ $order->ip_address ?? 'N/A' }}
                        </dd>
                    </div>
                </dl>
            </div>

            @if ($order->customerFeedback)
                <div
                    class="rounded-lg border p-6"
                    style="background-color: var(--color-surface); border-color: var(--color-border);"
                >
                    <h3 class="text-md font-bold mb-3 border-b pb-2" style="color: var(--color-accent-dark); border-color: var(--color-border);">
                        Customer Feedback
                    </h3>

                    <div class="text-sm">
                        <span class="font-semibold">Rating:</span>
                        <span class="font-bold text-amber-500">{{ $order->customerFeedback->rating }} / 5 Stars</span>

                        @if ($order->customerFeedback->comments)
                            <p class="mt-2 text-xs italic bg-gray-50 p-2 rounded border">
                                "{{ $order->customerFeedback->comments }}"
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
