@extends('laravel-usp-theme::master')

@section('styles')
@parent
  <link rel="stylesheet" href="css/inscricoes.css">
  <style>
    .atendente-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: orange !important;
    }

    .admin-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: red !important;
    }
  </style>
@endsection

@section('content')
  @include('messages.flash')
  @include('messages.errors')
@endsection

@section('javascripts_bottom')
@parent
  <script>
    $(function() {

      // vamos confirmar ao apagar um registro
      $(".delete-item").on("click", function() {
        return confirm("Tem certeza que deseja deletar?");
      });

      // ativando tooltip global
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
        html: true
      });

      // vamos aplicar o estilo de perfil no menubar
      @if (session('perfil') == 'atendente')
        $('#menu').find('.navbar').addClass('atendente-menubar');
      @endif
      @if (session('perfil') == 'admin')
        $('#menu').find('.navbar').addClass('admin-menubar');
      @endif
    });
  </script>

  <script>
    $('input.datepicker').datepicker({
      dateFormat: 'dd/mm/yy',
      closeText:"Fechar",
      prevText:"Anterior",
      nextText:"Próximo",
      currentText:"Hoje",
      monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
      monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
      dayNames:["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sábado"],
      dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],
      dayNamesMin:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
  <script src="{{ asset('js/datepicker.js') }}" type="text/javascript"></script>
@endsection
