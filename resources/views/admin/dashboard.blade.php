@extends('layouts.storefront')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--color-accent-dark);">
                Order Dashboard
            </h1>

            <p style="color: var(--color-text);">
                Signed in as {{ $admin->name }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button
                type="submit"
                class="rounded-md border px-4 py-2"
                style="border-color: var(--color-border); color: var(--color-text);"
            >
                Logout
            </button>
        </form>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
        @foreach ($statuses as $status)
            <section
                class="rounded-lg border p-4"
                style="background-color: var(--color-surface); border-color: var(--color-border);"
            >
                <h2 class="mb-4 font-bold" style="color: var(--color-accent-dark);">
                    {{ str_replace('_', ' ', ucfirst($status->value)) }}
                </h2>

                <div class="space-y-4">
                    @foreach (($orders[$status->value] ?? collect()) as $order)
                        <article class="rounded-md border p-4 transition-colors hover:border-amber-400" style="border-color: var(--color-border);">
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="font-semibold hover:underline block mb-1 text-base"
                                style="color: var(--color-accent-dark);"
                            >
                                {{ $order->customer_name }}
                            </a>

                            <p class="text-sm" style="color: var(--color-text);">
                                {{ $order->phone }}
                            </p>

                            <p class="mt-2 text-sm" style="color: var(--color-text);">
                                {{ $order->delivery_date->format('Y-m-d') }}
                                ·
                                {{ $order->delivery_slot }}
                            </p>

                            <p class="mt-2 font-semibold" style="color: var(--color-accent-dark);">
                                {{ number_format((float) $order->total_amount, 2) }}
                                JOD
                            </p>

                            <form
                                method="POST"
                                action="{{ route('admin.orders.status', $order) }}"
                                class="mt-4"
                            >
                                @csrf
                                @method('PATCH')

                                <select
                                    name="status"
                                    class="w-full rounded-md border px-2 py-1 text-sm"
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
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>
@endsection
