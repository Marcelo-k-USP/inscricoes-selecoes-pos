@extends('master')

@section('content')
@parent
  @auth
    <script>window.location = "inscricoes";</script>
  @else
    Realize sua inscrição ou, para acessar suas inscrições ou funcionalidades administrativas, <a href="login"> Faça seu Login! </a>
  @endauth
@endsection
