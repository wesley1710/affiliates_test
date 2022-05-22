<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Affiliates') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <section class="container">
        <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
            <span class="fs-4">Affiliates up to {{ $kmLimit }} km</span>
        </header>
    </section>

    <section class="container">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Distance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($affiliates as $affiliate)
                        <tr>
                            <td scope="col">{{ $affiliate->affiliate_id }}</td>
                            <td scope="col">{{ $affiliate->name }}</td>
                            <td scope="col">{{ number_format($affiliate->distance, 2) }} km</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
