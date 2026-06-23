@section('styles')
@parent
<style>
  #card-tipoarquivo-principal {
    border: 1px solid coral;
    border-top: 3px solid coral;
  }
</style>
@endsection

{{ html()->form('post', 'tiposarquivo' . (($modo == 'edit') ? ('/edit/' . $tipoarquivo->id) : '/create'))
  ->attribute('id', 'form_principal')
  ->open() }}
  @csrf
  @method($modo == 'edit' ? 'put' : 'post')
  {{ html()->hidden('id') }}
  <input type="hidden" name="classe_nome_hidden" id="classe_nome_hidden">
  <div class="card mb-3 w-100" id="card-tipoarquivo-principal">
    <div class="card-header">
      Informações básicas
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        @include('common.list-table-form-contents')
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">{{ ($modo == 'edit' ) ? 'Salvar' : 'Prosseguir' }}</button>
      </div>
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#classe_nome option').filter(function() {
        return $(this).text() === 'Inscrições';
      }).text('Inscrições/Matrículas');

      @if ($modo == 'edit')
        $('#classe_nome').attr('disabled', true);                 // quando desabilitamos um select, o campo dele não sobe no submit do form
		    $('#classe_nome_hidden').val($('#classe_nome').val());    // então vamos jogar o valor do select para um campo hidden, e no submit renomear os campos
      @endif

      $('#form_principal').find('input, select').filter(':visible').not(':disabled').first().focus();
      updateObrigatorio();
      updateMinimo();
    });

    $('#form_principal').on('submit', function(event) {
      // transforma classe_nome_hidden em classe_nome, para que suba devidamente no submit para o controller
      $('#classe_nome').attr('id', 'classe_nome_original');
      $('#classe_nome').attr('name', 'classe_nome_original');
      $('#classe_nome_hidden').attr('name', 'classe_nome');
      $('#classe_nome_hidden').attr('id', 'classe_nome');
    });

    $('#classe_nome').on('change', function () {
      updateObrigatorio();
    });

    $('#obrigatorio').on('change', function () {
      updateMinimo();
    });

    function updateObrigatorio() {
      if ($('#classe_nome').val() === 'Seleções' || $('#classe_nome option:selected').text() === 'Seleções') {
        if ($('#obrigatorio').val() === 'Condicional')    // se a opção 'Condicional' estava selecionada...
          $('#obrigatorio').val('').trigger('change');    // ... forçamos a mudança para 'Não'
        $('#obrigatorio option[value="Condicional"]').attr('disabled', true).hide();
      } else
        $('#obrigatorio option[value="Condicional"]').removeAttr('disabled').show();
    }

    function updateMinimo() {
      if ($('#obrigatorio').val() === 'Sim') {
        $('#obrigatorio_condicao_campo').val('');
        $('#obrigatorio_condicao_campo').parents('div').eq(1).hide();
        $('#obrigatorio_condicao_valor').val('');
        $('#obrigatorio_condicao_valor').parents('div').eq(1).hide();
        $('#minimo').parents('div').eq(1).show();
      } else if ($('#obrigatorio').val() === 'Condicional') {
        $('#obrigatorio_condicao_campo').parents('div').eq(1).show();
        $('#obrigatorio_condicao_valor').parents('div').eq(1).show();
        $('#minimo').parents('div').eq(1).show();
      } else {
        $('#obrigatorio_condicao_campo').val('');
        $('#obrigatorio_condicao_campo').parents('div').eq(1).hide();
        $('#obrigatorio_condicao_valor').val('');
        $('#obrigatorio_condicao_valor').parents('div').eq(1).hide();
        $('#minimo').val('');
        $('#minimo').parents('div').eq(1).hide();
      }
    }
  </script>
@endsection
