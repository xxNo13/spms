<x-guest-layout>

    <div id="auth-left" class="bg-white rounded-3">
        <div class="auth-logo">
            <a href="/"><img src="{{ asset('/images/logo/logo-green.png') }}" alt="Logo"></a>
        </div>
        <h1 class="auth-title">Login.</h1>
        <p class="auth-subtitle mb-3 lh-sm">Log in with your DNSC email and password.</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
                <input class="form-control form-control-lg" type="email" name="email" placeholder="Email"
                    value="{{ old('email') }}">
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password"
                    placeholder="Password">
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <div class="form-check form-check-lg d-flex align-items-end">
                <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                <label class="form-check-label text-gray-600" for="flexCheckDefault">
                    Keep me logged in
                </label>
            </div>
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Login</button>
        </form>
        <div class="text-center mt-5 text-lg fs-5">
            @if (Route::has('register'))
            <p class="text-gray-600">Don't have an account? <a href="{{route('register')}}" class="font-bold">Sign
                    up</a>.</p>
            @endif


            @if (Route::has('password.request'))
            <p><a class="font-bold" href="{{route('password.request')}}">Forgot password?</a>.</p>
            @endif
        </div>
    </div>
</x-guest-layout>