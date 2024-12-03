@extends('master')

@section('content')
@parent
  @auth
    <script>window.location = "inscricoes";</script>
  @else
    Candidato, se você tiver número USP, <a href="login">faça seu login usando a senha única</a>.<br />
    Se você não tiver número USP, <a href="inscricoes/create">realize sua inscrição</a> ou, para acessar suas inscrições, <a href="???">faça seu login usando a senha cadastrada no ato da inscrição</a>.<br />
    <br />
    Administrativo, <a href="login">faça seu login com a senha única USP</a>.
  @endauth
@endsection
