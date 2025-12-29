@extends('layouts.default')

@section('content')
<section class="flex min-h-[70vh] items-center justify-center bg-gray-50 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl p-8 border border-gray-100">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-main-red/10">
                <svg class="h-8 w-8 text-main-red" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M9 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Đăng nhập</h2>
            <p class="text-gray-500 text-sm">Chào mừng bạn quay lại!</p>
        </div>

        @if (session('status'))
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        try {
                            if (window.showToast) {
                                window.showToast({!! json_encode(session('status')) !!}, { type: 'default', duration: 4000 });
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    });
                </script>
            @endpush
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-500">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" required autofocus autocomplete="email"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition @error('email') border-red-500 @enderror" />
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 focus:border-main-red focus:ring-2 focus:ring-main-red/30 focus:outline-none transition" />
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-main-red shadow-sm focus:ring-main-red/30">
                    Ghi nhớ đăng nhập
                </label>
                <a href="{{ route('forgot') }}" class="text-sm text-main-red hover:underline">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="w-full rounded-full bg-main-red hover:bg-main-red-hover transition text-white font-bold py-2.5 mt-2 shadow-lg text-lg tracking-wide focus:outline-none focus:ring-2 focus:ring-main-red/30">Đăng nhập</button>
        </form>
        <div class="mt-8 text-center text-sm text-gray-500">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="text-main-red hover:underline font-semibold">Đăng ký ngay</a>
        </div>
    </div>
</section>
@endsection
