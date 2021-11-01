@extends('layouts.admin')

@section('title', 'Cadastro')

@section('content')

    <div class="form-login-register">
        @if($errors->any())
            @component('components.alertWarning')
                @slot('type')
                    Error: 
                @endslot
                <ul>
                    @foreach($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            @endcomponent
        @endif

        <h1>Cadastrar</h1>

        <form method="post">
            @csrf
            <input type="text" name="name" placeholder="Digite o seu nome" value="{{ old('name') }}"> <br>
            <input type="email" name="email" placeholder="Digite seu e-mail" value="{{ old('email') }}"> <br>
            <input type="password" name="password" placeholder="Digite a sua senha"> <br>
            <input type="password" name="password_confirmation" placeholder="Confirme a sua senha"> <br>
            <input type="submit" value="Cadastrar">
            <a href="{{ route('login') }}">[ Login ]</a>
        </form>
    </div>

@endsection
