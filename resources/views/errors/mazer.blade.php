<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/error.css') }}">
    <link rel="icon" href="{{ asset('images/logo/icon.png') }}">
</head>
<body>
    <script src="assets/js/initTheme.js"></script>
    <div id="error">
      <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
          <div class="text-center">
            <img class="img-error" src="{{ asset('images/samples') . '/' }}@yield('image')" alt="Not Found">
            <h1 class="error-title">
                @yield('message')
            </h1>
            @if ($__env->yieldContent('url') == 'dashboard')
              <a href="{{ route('dashboard') }}" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
            @else
              <a href="javascript:window.location.href=window.location.href" class="btn btn-lg btn-outline-primary mt-3">Refresh</a>
            @endif
          </div>
        </div>
      </div>
    </div>
</body>
</html>