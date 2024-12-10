@extends('master')

@section('styles')
@parent
<style>
  #card-parametros {
    border: 1px solid coral;
    border-top: 3px solid coral;
  }
</style>
@endsection

@section('content')
@parent
  <div class="row">
    <div class="col-md-7">
      {{ html()->form('post', '')->attribute('id', 'form_parametros')->open() }}
        @csrf
        @method('put')
        {{ html()->hidden('id') }}
        <div class="card mb-3 w-100" id="card-parametros">
          <div class="card-header">
            Editar Parâmetros
          </div>
          <div class="card-body">
            <div class="list_table_div_form">
              @php
                $modo = 'create';
              @endphp
              @foreach ($fields as $col)
                @if (empty($col['type']) || $col['type'] == 'text')
                  @include('common.list-table-form-text')
                @elseif ($col['type'] == 'number')
                  @include('common.list-table-form-number')
                @endif
              @endforeach
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </div>
          </div>
        </div>
      {{ html()->form()->close() }}
    </div>
  </div>
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(this).find(':input[type=text]').filter(':visible:first').focus();

      // preenchendo o form com os valores a serem editados
      var parametros = {!! json_encode($parametros) !!};
      var inputs = $("#form_parametros :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
      inputs.each(function() {
        $(this).val(parametros[this.name]);
        if ($(this).attr('oninput') == 'validateInput(this)')
          $(this).val(formatarDecimal($(this).val()));
      });
    });

    function validateInput(input) {
      // remove qualquer caractere que não seja dígito ou vírgula
      input.value = input.value.replace(/[^0-9,]/g, '');

      // remove toda vírgula após a primeira vírgula
      var pos_primeira_virgula = input.value.indexOf(',');
      if (pos_primeira_virgula !== -1) {
        var string_antes_primeira_virgula = input.value.substring(0, pos_primeira_virgula + 1);
        var string_depois_primeira_virgula = input.value.substring(pos_primeira_virgula + 1).replace(/,/g, '');
        input.value = string_antes_primeira_virgula + string_depois_primeira_virgula;
      }
    }
  </script>
@endsection
