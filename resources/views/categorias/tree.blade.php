@extends('master')

@section('content')
@parent
  @if ($categorias != null)
    @include('categorias.partials.main')
    @include('categorias.partials.modal')
  @else
    Sem categoria
  @endif
@endsection

@section('javascripts_bottom')
@parent
  <script>
    $(document).ready(function() {
      if (location.hash) {    // se houver anchor na url, vamos abrir os detalhes
        $('#detalhes_' + location.hash.substring(1)).collapse('show');
        console.log('abrindo #detalhes_' + window.location.hash.substring(1));
      }

      $("[data-collapse-group='myDivs']").click(function() {
        var $this = $(this);
        $("[data-collapse-group='myDivs']:not([data-target='" + $this.data("target") + "'])").each(function() {
          $($(this).data("target")).removeClass("in").addClass('collapse');
        });
      });
    });
  </script>
@endsection
