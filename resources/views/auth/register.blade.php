@extends('layouts.default')

@section('content')
<section class="flex min-h-[70vh] items-center justify-center bg-gray-50 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl p-8 border border-gray-100">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-main-red/10">
                <svg class="h-8 w-8 text-main-red" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M9 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Đăng ký</h2>
            <p class="text-gray-500 text-sm">Tạo tài khoản mới để mua sắm dễ dàng hơn.</p>
        </div>
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                <input id="name" type="text" name="name" required autofocus autocomplete="name" value="{{ old('name') }}"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition" />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" required autocomplete="email" value="{{ old('email') }}"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition" />
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition" />
            </div>
            <button type="submit" class="w-full rounded-full bg-main-red hover:bg-main-red-hover transition text-white font-bold py-2.5 mt-2 shadow-lg text-lg tracking-wide focus:outline-none focus:ring-2 focus:ring-main-red/30">Đăng ký</button>
        </form>
        <div class="mt-8 text-center text-sm text-gray-500">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="text-main-red hover:underline font-semibold">Đăng nhập</a>
        </div>
    </div>
</section>
@endsection
