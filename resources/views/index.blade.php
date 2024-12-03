@extends('master')

@section('content')
@parent
  @auth
    <script>window.location = "inscricoes";</script>
  @else
    <a href="inscricoes/create">Realize sua inscrição</a> ou, para acessar suas inscrições ou funcionalidades administrativas, <a href="login">faça seu login</a>.
  @endauth
@endsection
