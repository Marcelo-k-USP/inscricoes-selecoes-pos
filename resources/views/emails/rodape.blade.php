<br />
<br />
<br />
<div>
  ----------<br />
  Este é um email automático - não responda.<br />
  <br />
  <a href="{{ config('app.url') }}">Sistema {{ config('app.name') }}</a><br />
  @section('skin_footer')
    @include('laravel-usp-theme::partials.skins.' . config('laravel-usp-theme.skin') . '.footer')
  @endsection
  @yield('skin_footer')
</div>
