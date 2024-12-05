@extends('master')

@section('content')
@parent
  @auth
    <script type="text/javascript">window.location = "/inscricoes";</script>
  @else
    <div class="d-flex justify-content-center">
      <form method="POST" action="/localusers/login">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Login Local</h1>
        <label for="email" class="sr-only">E-mail</label>
        <input class="form-control" type="text" name="email" id="email" placeholder="E-mail" autofocus>
        <label for="password" class="sr-only">Senha</label>
        <input class="form-control mb-4" type="password" name="password" id="password" placeholder="Senha">
        <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
      </form>
    </div>
  @endauth
@endsection
