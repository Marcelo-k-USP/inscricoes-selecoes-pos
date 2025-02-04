<!-- Modal que atende adicionar e editar tipos de arquivo -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Adicionar/Editar Tipos de Arquivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{ html()->form('post', '')->open() }}
            @csrf
            @method('post')
            {{ html()->hidden('id') }}
            <input type="hidden" name="classe_nome_hidden" id="classe_nome_hidden">
            @php
              $modo = 'create';
            @endphp
            @foreach ($fields as $col)
              @if (empty($col['type']) || $col['type'] == 'text')
                @include('common.list-table-form-text')
              @elseif ($col['type'] == 'select')
                @include('common.list-table-form-select')
              @elseif ($col['type'] == 'checkbox')
                @include('common.list-table-form-checkbox')
              @elseif ($col['type'] == 'integer')
                @include('common.list-table-form-integer')
              @endif
            @endforeach
            <div class="text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          {{ html()->form()->close() }}
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      $('#modalForm').on('shown.bs.modal', function() {
        $(this).find('input, select').filter(':visible').not(':disabled').first().focus();
      });

      $('#modalForm').on('hidden.bs.modal', function() {
        if ($('#classe_nome_original').length) {
          // volta os nomes dos dois campos ao estado inicial
          $('#classe_nome').attr('name', 'classe_nome_hidden');
          $('#classe_nome').attr('id', 'classe_nome_hidden');
          $('#classe_nome_original').attr('name', 'classe_nome');
          $('#classe_nome_original').attr('id', 'classe_nome');
        }
      });

      edit_form = function(id) {
        $.get('tiposarquivo/' + id
          , function(row) {
            console.log(row);
            // mudando para PUT
            $('#modalForm :input').filter("input[name='_method']").val('PUT');

            // preenchendo o form com os valores a serem editados
            var inputs = $("#modalForm :input").not(":input[type=checkbox], :input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
            inputs.each(function() {
              $(this).val(row[this.name]);
              console.log(this.name);
            });
            $("#modalForm :input[type=checkbox]").each(function() {
              $(this).prop('checked', row[this.name]);
              console.log(this.name);
            });
            updateMinimo();

            // Ajustando action
            $('#modalForm').find('form').attr('action', 'tiposarquivo/' + id);

            // Ajustando o title
            $('#modalLabel').html('Editar tipo de arquivo');

            $('#classe_nome').attr('disabled', true);                 // quando desabilitamos um select, o campo dele não sobe no submit do form
            $('#classe_nome_hidden').val($('#classe_nome').val());    // então vamos jogar o valor do select para um campo hidden, e renomear os campos
            $('#classe_nome').attr('name', 'classe_nome_original');
            $('#classe_nome').attr('id', 'classe_nome_original');
            $('#classe_nome_hidden').attr('name', 'classe_nome');
            $('#classe_nome_hidden').attr('id', 'classe_nome');

            $("#modalForm").modal();
            console.log('inputs', inputs);
          });
      };

      add_form = function(id) {
          $("#modalForm :input").filter("input[type='text']").val('');

          // preenchendo o form com os valores a serem editados
          $("#modalForm select").val(id);

          // Ajustando action
          $('#modalForm').find('form').attr('action', 'tiposarquivo');

          $('#modalLabel').html('Adicionar Tipo de Arquivo');
          $('#modalForm :input').filter("input[name='_method']").val('POST');

          updateMinimo();

          $('#classe_nome').attr('disabled', false);

          $("#modalForm").modal();
      };

      $('#obrigatorio').on('click', function () {
        updateMinimo();
      });

      function updateMinimo() {
        if (!$('#obrigatorio').prop('checked')) {
          $('#minimo').val('');
          $('#minimo').parents('div').eq(1).hide();
        } else
          $('#minimo').parents('div').eq(1).show();
      }
    });
  </script>
@endsection
