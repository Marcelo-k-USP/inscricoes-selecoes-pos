@extends('master')

@section('content')
@parent
  @auth
    Utilize o menu acima para navegar pelo sistema.
  @else
    <div class="d-flex justify-content-center">
      <h1 class="h3 mb-3 font-weight-normal"><a href="localusers/login">Candidatos</a></h1>
    </div>
    <div class="d-flex justify-content-center">
      <h1 class="h3 mb-3 font-weight-normal"><a href="login">Gestores</a></h1>
    </div>
  @endauth
@endsection
