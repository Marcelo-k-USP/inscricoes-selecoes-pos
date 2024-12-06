@extends('master')

@section('content')
@parent    {{-- sem isto, não inclui o messages.errors, apesar de estender master que estende layouts.app que inclui messages.errors --}}
  @auth
    <script type="text/javascript">window.location = "/inscricoes";</script>
  @else
    <div class="d-flex justify-content-center" style="margin-left: 110px;">
      <form method="POST" action="{{ route('localusers.redefinesenha') }}" id="form_reset_password">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Redefinir Senha</h1>
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label for="password" class="sr-only" style="margin-top: -20px;">Nova Senha</label>
        <div style="display: flex; align-items: center;">
          <div style="position: relative; margin-top: -20px;">
            <input class="form-control" style="width: 240px; padding-right: 30px;" type="password" name="password" id="password" placeholder="Nova Senha" autofocus>
            <a href="javascript:void(0);" onclick="toggle_senha('password')">
              <img src="/icons/view.png" id="toggle_icon_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
            </a>
          </div>
          <div id="strength-wrapper" style="margin-left: 10px;">
            <div style="height: 0px; width: 100px;">&nbsp;</div>
            <div id="barra_forca_senha" style="height: 10px; width: 0px;">&nbsp;</div>
            <p id="texto_forca_senha" style="margin-top: 5px;">&nbsp;</p>
          </div>
        </div>

        <label for="password_confirmation" class="sr-only">Confirmar Nova Senha</label>
        <div style="position: relative; margin-top: -10px;">
          <input class="form-control mb-4" style="width: 240px; padding-right: 30px;" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar Nova Senha">
          <a href="javascript:void(0);" onclick="toggle_senha('password_confirmation')">
            <img src="/icons/view.png" id="toggle_icon_password_confirmation" style="position: absolute; right: 120px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
          </a>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block" style="margin-top: -5px; width: 240px;">Redefinir Senha</button>
      </form>
    </div>
  @endauth
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $('#password').on('input', function () {
      validar_forca_senha($(this).val());
    });
  </script>
@endsection
