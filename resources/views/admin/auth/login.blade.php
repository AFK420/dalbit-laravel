@extends('layouts.storefront')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div
        class="rounded-lg border p-6 sm:p-8"
        style="background-color: var(--color-surface); border-color: var(--color-border);"
    >
        <h1
            class="mb-6 text-2xl font-bold"
            style="color: var(--color-accent-dark);"
        >
            Admin Login
        </h1>

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc px-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <label for="email" class="mb-1 block font-medium">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="mb-4 w-full rounded-md border px-4 py-2"
            >

            <label for="password" class="mb-1 block font-medium">
                Password
            </label>

            <input
                id="password"
                name="password"
                type="password"
                required
                class="mb-6 w-full rounded-md border px-4 py-2"
            >

            <button
                type="submit"
                class="w-full rounded-md px-6 py-3 font-semibold"
                style="background-color: var(--color-accent-dark); color: var(--color-bg);"
            >
                Sign in
            </button>
        </form>
    </div>
</div>
@endsection
