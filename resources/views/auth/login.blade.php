@extends('layouts.admin')

@section('title', 'Login')

@section('content')

    <div class="form-login-register">
        @if(session('warning'))
            @component('components.alertWarning')
                @slot('type')
                    Aviso:
                @endslot
                {{ session('warning') }}
            @endcomponent
        @endif

        @if(session('success'))
            @component('components.alertSuccess')
                @slot('type')
                    Aviso:
                @endslot
                {{ session('success') }}
            @endcomponent
        @endif

        <h1>Login</h1>

        <form method="post" style="margin: 25px 0px 25px 0px;">
            @csrf
            <input type="email" name="email" placeholder="Digite seu e-mail"> <br><br>
            <input type="password" name="password" placeholder="Digite a sua senha"> <br><br>
            <input type="submit" value="Entrar">

            <a href="{{ route('register') }}">[ Cadastrar ]</a>
        </form>


    </div>

@endsection
