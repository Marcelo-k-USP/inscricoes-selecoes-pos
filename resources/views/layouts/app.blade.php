@extends('laravel-usp-theme::master')

@section('styles')
@parent
  <link rel="stylesheet" href="css/selecoes-pos.css">
  <style>
    .docente-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: yellow !important;
    }

    .gerente-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: orange !important;
    }

    .admin-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: red !important;
    }

    .form-group .flatpickr-calendar {
      top: 0px !important;
      height: 36px !important;
      width: 86px !important;
      border-radius: .2rem !important;
    }

    .form-group .flatpickr-time {
      position: relative !important;
      top: -2px !important;
      border: 0 !important;
    }

    .form-group .flatpickr-hour, .form-group .flatpickr-time-separator, .form-group .flatpickr-minute {
      font-size: 1rem !important;
      font-weight: 400 !important;
      color: #495057 !important;
    }
  </style>
@endsection

@section('content')
  @include('messages.flash')
  @include('messages.errors')
@endsection

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
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
      @if (session('perfil') == 'docente')
        $('#menu').find('.navbar').addClass('docente-menubar');
      @endif
      @if (session('perfil') == 'gerente')
        $('#menu').find('.navbar').addClass('gerente-menubar');
      @endif
      @if (session('perfil') == 'admin')
        $('#menu').find('.navbar').addClass('admin-menubar');
      @endif
    });

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

  {{-- datepicker --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
  <script src="{{ asset('js/datepicker.js') }}" type="text/javascript"></script>

  {{-- flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
  <script src="{{ asset('js/timefield.js') }}" type="text/javascript"></script>
@endsection
