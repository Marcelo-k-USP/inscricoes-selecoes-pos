@extends('master')

@section('content')
    @parent
        @auth
            <script>window.location = "inscricoes";</script>
        @else
            Você ainda não fez seu login com a senha única USP <a href="login"> Faça seu Login! </a>
        @endauth
@endsection
