@extends('layouts.admin')

@section('title', 'Edição')

@section('content')

    <a href="{{ route('home') }}" class="btn-back-edit"><< Voltar</a>

    @if(session('warning'))
        @component('components.alertWarning')
            @slot('type')
                Aviso:
            @endslot
            {{ session('warning') }}
        @endcomponent
    @endif

    <div class="main">

        <h1 class="title-list">Editar gato</h1>

        <div class="form-edit-cat">
            @if(empty($cat['image']))
                @php($cat['image'] = asset('images/cat-default.jpg'))
            @endif
            <img src="{{ $cat['image'] }}" alt="Imagem do gatinho {{ $cat['name'] }}" class="img-edit">
            <form method="post">
                @csrf
                <input type="hidden" name="id_cat" value="{{ $cat['id'] }}">
                <label for="name">Nome: </label>
                <input type="text" name="name" id="name" value="{{ $cat['name'] }}">

                <label for="description">Descrição: </label>
                <textarea name="description" id="description" cols="40" rows="10">{{ $cat['description'] }}</textarea>

                <input type="submit" value="Salvar" class="btn-edit">
            </form>
        </div>

    </div>
@endsection
