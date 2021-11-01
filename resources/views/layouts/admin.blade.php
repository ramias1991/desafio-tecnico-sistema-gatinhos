<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>@yield('title') - Laravel</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="{{ asset('images/inovadora.png')}}" alt="">
        </div>
    </header>
    <hr>

    <section class="container">
        @yield('content')
    </section>
    <hr>

    <footer>
        Copyright &copy;  <a href="https://www.inovadora.com.br/"> Inovadora Sistamas</a>
    </footer>
</body>
</html>
