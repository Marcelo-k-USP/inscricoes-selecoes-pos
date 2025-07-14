@extends('master')

@section('content')
@parent    {{-- sem isto, não inclui o messages.errors, apesar de estender master que estende layouts.app que inclui messages.errors --}}
  @auth
    <script type="text/javascript">window.location = "/inscricoes";</script>
  @else
    <div class="d-flex justify-content-center">
      <form method="POST" action="/localusers/login" id="form_diversasacoes">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Login Local</h1>
        <label for="email" class="sr-only">E-mail</label>
        <input class="form-control" type="text" name="email" id="email" placeholder="E-mail" autofocus>
        <label for="password" class="sr-only">Senha</label>
        <div style="position: relative;">
          <input class="form-control mb-4" style="width: 100%; padding-right: 30px;" type="password" name="password" id="password" placeholder="Senha">
          <a href="javascript:void(0);" onclick="toggle_password('password')">
            <img src="/images/view.png" id="toggle_icon_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
          </a>
        </div>
        <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
        <a href="javascript:void(0);" onclick="esqueceu_senha()" style="text-decoration: none; color: #007bff; font-size: 11px;">Esqueceu sua senha?</a>
        <br />
        <br />
        <h1 class="h3 mb-3 font-weight-normal">ou <a href="localusers/create" style="text-decoration: none; color: #007bff;">cadastre-se</a></h1>
        @if (session('alert-danger') === 'E-mail não confirmado')
          <h1 class="h3 mb-3 font-weight-normal">ou
            <a href="javascript:void(0);" onclick="reenvia_email_confirmacao()" style="text-decoration: none; color: #007bff;">receba novamente<br />o e-mail para confirmação</a>
          </h1>
        @endif
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
      $('#form_diversasacoes').attr('action', 'localusers/esqueceusenha');
      $('#form_diversasacoes').submit();
    }

    function reenvia_email_confirmacao()
    {
      $('#form_diversasacoes').attr('action', 'localusers/reenviaemailconfirmacao');
      $('#form_diversasacoes').submit();
    }
  </script>
@endsection
