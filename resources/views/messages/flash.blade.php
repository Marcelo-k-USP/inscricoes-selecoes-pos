<div class="flash-message fixed-bottom w-75 ml-auto mr-auto">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if (Session::has('alert-' . $msg))
      <p class="alert alert-{{ $msg }}">{!! Session::get('alert-' . $msg) !!}
        <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
      </p>
      @php
        Session::forget('alert-' . $msg);    // tendo exibido o alert, remove-o da sessão para que ele não seja reexibido por exemplo com um Ctrl+F5
      @endphp
    @endif
  @endforeach
</div>

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(function() {
      $(".flash-message").fadeTo(5000, 500).slideUp(500, function() {
        $(".flash-message").slideUp(500);
      });
    });
  </script>
@endsection
