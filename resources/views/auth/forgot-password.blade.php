<x-guest-layout>
    <div id="auth-left" class="position-absolute top-50 start-50 translate-middle w-100">
        <div class="auth-logo">
            <a href="/"><img src="{{ asset('/images/logo/logo-green.png') }}" alt="Logo"></a>
        </div>
        <h1 class="auth-title">Forgot Password</h1>
        <p class="auth-subtitle mb-3 lh-sm">Input your email and we will send you reset password link.</p>

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
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
                <input type="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" name="email">
                <div class="form-control-icon">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Send Password Reset Link</button>
        </form>
        <div class="text-center mt-5 text-lg fs-5">
            <p class='text-gray-600'>Remember your account? <a href="{{ route('login')}}" class="font-bold">Log
                    in</a>.
            </p>
        </div>
    </div>
   
</x-guest-layout>
