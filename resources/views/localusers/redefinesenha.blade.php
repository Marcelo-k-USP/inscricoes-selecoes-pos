@extends('master')

@section('content')
@parent    {{-- sem isto, não inclui o messages.errors, apesar de estender master que estende layouts.app que inclui messages.errors --}}
  @auth
    <script type="text/javascript">window.location = "/inscricoes";</script>
  @else
    <div class="d-flex justify-content-center">
      <form method="POST" action="{{ route('localusers.redefinesenha') }}" id="form_reset_password">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Redefinir Senha</h1>
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label for="password" class="sr-only">Nova Senha</label>
        <div style="position: relative;">
          <input class="form-control" type="password" name="password" id="password" placeholder="Nova Senha" autofocus>
          <a href="javascript:void(0);" onclick="toggle_senha('password')">
            <img src="/icons/view.png" id="toggle_icon_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
          </a>
        </div>

        <label for="password_confirmation" class="sr-only">Confirmar Nova Senha</label>
        <div style="position: relative;">
          <input class="form-control mb-4" style="width: 100%; padding-right: 30px;" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar Nova Senha">
          <a href="javascript:void(0);" onclick="toggle_senha('password_confirmation')">
            <img src="/icons/view.png" id="toggle_icon_password_confirmation" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 20px; height: 20px;">
          </a>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block">Redefinir Senha</button>
      </form>
    </div>
  @endauth
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
@endsection
