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
      @if ($modo == 'edit')
        $('#classe_nome').attr('disabled', true);                 // quando desabilitamos um select, o campo dele não sobe no submit do form
		    $('#classe_nome_hidden').val($('#classe_nome').val());    // então vamos jogar o valor do select para um campo hidden, e no submit renomear os campos
      @endif

      $('#form_principal').find('input, select').filter(':visible').not(':disabled').first().focus();
      updateMinimo();
      updateAlunoEspecial();
    });

    $('#form_principal').on('submit', function(event) {
      // transforma classe_nome_hidden em classe_nome, para que suba devidamente no submit para o controller
      $('#classe_nome').attr('id', 'classe_nome_original');
      $('#classe_nome').attr('name', 'classe_nome_original');
      $('#classe_nome_hidden').attr('name', 'classe_nome');
      $('#classe_nome_hidden').attr('id', 'classe_nome');
    });

    $('#obrigatorio').on('click', function () {
      updateMinimo();
    });

    $('#classe_nome').on('change', function () {
      updateAlunoEspecial();
    });

    function updateMinimo() {
      if (!$('#obrigatorio').prop('checked')) {
        $('#minimo').val('');
        $('#minimo').parents('div').eq(1).hide();
      } else
        $('#minimo').parents('div').eq(1).show();
    }

    function updateAlunoEspecial() {
      if ($('#classe_nome').val() != 'Inscrições') {
        $('#aluno_especial').prop('checked', false);    // muito poucos tipos de documento são exigidos para alunos especiais, então deixamos default como desmarcado aqui
        $('#aluno_especial').closest('div.form-group').hide();
      } else
        $('#aluno_especial').closest('div.form-group').show();
    }
  </script>
@endsection
