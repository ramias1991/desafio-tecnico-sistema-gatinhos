@extends('layouts.admin')

@section('title', 'Lista de Gatos')

@section('content')

    @if(session('success'))
        @component('components.alertSuccess')
            @slot('type')
                Aviso:
            @endslot
            {{ session('success') }}
        @endcomponent
    @endif

    @if(session('warning'))
        @component('components.alertWarning')
            @slot('type')
                Aviso:
            @endslot
            {{ session('warning') }}
        @endcomponent
    @endif

    @if(isset($_COOKIE['success']) && !empty($_COOKIE['success']))
        @component('components.alertSuccess')
            @slot('type')
                Aviso:
            @endslot
            {{ $_COOKIE['success'] }}
            @if(isset($_COOKIE['success_list']))
                @php
                    $listCatsAdded = explode('-', $_COOKIE['success_list']);
                @endphp
                <ul style="margin-left: 15px;">
                    @foreach($listCatsAdded as $list_add)
                        @if(!empty($list_add))
                            <li>{{ $list_add }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        @endcomponent
    @endif

    @if(isset($warning) && !empty($warning))
        @component('components.alertWarning')
            @slot('type')
                Aviso:
            @endslot
            {{ $warning }}
        @endcomponent
    @endif

    <div class="main">
        <p class="welcome">Bem vindo(a), {{ $name }} <a href="{{ route('logout', ['msg' => 'Logout efetuado com sucesso.']) }}">[ Sair ]</a></p>

        <h1 class="title-list">Listagem de Gatos</h1>

        <div class="top-form-btn-clean">
            <form method="POST" class="form-search-cat">
                @csrf
                <input type="search" name="search-cat" placeholder="Pesquisar Gato" value="{{ (isset($_GET['searchCatName'])?$_GET['searchCatName']:'') }}">
                <input type="submit" value="Buscar">
            </form>

            @if(count($listCats) > 0)
            <a href="{{ route('clean-all') }}" class="clean-list-cats" onclick="return confirm('Deseja realmente limpar a lista?')">Limpar Tudo</a>
            @endif
        </div>

        @if(count($listCats) > 0)
            <div class="container-card-cat">
                @foreach($listCats as $cat)
                    <div class="card-cat">
                        <div class="card-cat-body">
                            <div class="img-cat">
                                @if(empty($cat->image))
                                    @php($cat->image = asset('images/cat-default.jpg'))
                                @endif
                                <img src="{{ $cat->image }}" alt="Imagem do gatinho">
                            </div>
                            <div class="info-cat">
                                <h3>{{ $cat->name }}</h3>
                                <p>{{ $cat->description }}</p>
                                <p class="last-edit"><strong>Última edição:</strong> {{ ($cat->updated_at==null)?'Nenhuma edição realizada':(date('d/m/Y \à\s H:i', strtotime($cat->updated_at))) }}</p>
                            </div>
                        </div>
                        <div class="card-cat-footer">
                            <a href="{{ route('edit-cat', ['id_cat' => $cat->id]) }}" class="edit-cat">Editar</a>
                            <a href="{{ route('delete-cat', ['id_cat' => $cat->id]) }}" class="delete-cat" onclick="return confirm('Deseja realmente excluir o gatinho?')">Excluir</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @component('components.alertWarning')
                @slot('type')
                    Aviso:
                @endslot
                Nenhum gatinho na lista ainda!
            @endcomponent
        @endif
    </div>
@endsection
