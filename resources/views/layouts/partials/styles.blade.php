<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

<!-- Vendors -->
<link rel="stylesheet" href="{{ asset('/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('/vendors/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('/vendors/toastify/toastify.css') }}">
<link rel="stylesheet" href="{{ asset('/vendors/iconly/bold.css') }}">
<link rel="stylesheet" href="{{ asset('/vendors/apexcharts/apexcharts.css') }}">



<!-- Styles -->
<link rel="stylesheet" href="{{ mix('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<link rel="stylesheet" href="{{ mix('css/app-dark.css') }}">

<link rel="icon" href="{{ asset('images/logo/icon.png') }}">
@livewireStyles

{{ $style ?? '' }}