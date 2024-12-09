@extends('master')

@section('content')
@parent    {{-- sem isto, não inclui o messages.errors, apesar de estender master que estende layouts.app que inclui messages.errors --}}
  @auth
    <script type="text/javascript">window.location = "/inscricoes";</script>
  @else
    <div class="d-flex justify-content-center">
      <form method="POST" action="/localusers/login" id="form_login">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Login Local</h1>
        <label for="email" class="sr-only">E-mail</label>
        <input class="form-control" type="text" name="email" id="email" placeholder="E-mail" autofocus>
        <label for="password" class="sr-only">Senha</label>
        <div style="position: relative;">
          <input class="form-control mb-4" style="width: 100%; padding-right: 30px;" type="password" name="password" id="password" placeholder="Senha">
          <a href="javascript:void(0);" onclick="toggle_password('password')">
            <img src="/icons/view.png" id="toggle_icon_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
          </a>
        </div>
        <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
        <a href="javascript:void(0);" onclick="esqueceu_senha()" style="text-decoration: none;"><small style="color: red;">Esqueceu sua senha?</small></a>
      </form>
    </div>
  @endauth
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    function esqueceu_senha()
    {
      $('#form_login').attr('action', 'localusers/esqueceusenha');
      $('#form_login').submit();
    }
  </script>
@endsection
