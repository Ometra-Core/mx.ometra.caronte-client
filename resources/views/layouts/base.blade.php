<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/icon.png" type="image/icon type">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('vendor/caronte/css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    @include('caronte::partials.messages')
    <main role="main">
        @yield('content')
    </main>
</body>

@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('vendor/caronte/js/caronte-management/create.js') }}"></script>
<script src="{{ asset('vendor/caronte/js/caronte-management/roles.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const mess = document.querySelector(".alert.alert-success.alert-dismissible");
            if (mess) {
                mess.remove();
            }
        }, 3500)
    });
</script>

</html>
