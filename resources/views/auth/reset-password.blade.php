<x-guest-layout>
    <div id="auth-left">
        <div class="auth-logo">
            <a href="/"><img src="{{ asset('/images/logo/logo-green.png') }}" alt="Logo"></a>
        </div>
        <h1 class="auth-title">Reset Password</h1>
        <p class="auth-subtitle mb-5">Input your new password.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group position-relative has-icon-left mb-4">
                <input class="form-control form-control-xl" type="email" name="email" placeholder="Email"
                    value="{{ $request->email }}">
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
                <input id="password" type="password" class="password-padding form-control form-control-xl" name="password" placeholder="Password"
                    placeholder="Password">
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <i class="bi bi-eye-slash password-show-hide" id="toggleshow2"></i>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
                <input id="password_confirmation" type="password" class="password-padding form-control form-control-xl" name="password_confirmation" placeholder="Password"
                    placeholder="Password">
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <i class="bi bi-eye-slash password-show-hide" id="toggleshow3"></i>
            </div>

            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Reset Password</button>
        </form>
    </div>
   
</x-guest-layout>