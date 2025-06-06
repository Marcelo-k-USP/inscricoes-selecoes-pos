<button type="button" class="btn btn-primary btn-sm" onclick="json_modal_form()">
  <i class="fas fa-plus"></i> Adicionar {{ str_replace('_', ' ', ucwords($field)) }}
</button>

<!-- Modal -->
<div class="modal fade" id="json-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalShowJson" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalShowJson">Adicionar {{ str_replace('_', ' ', ucwords($field)) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{ html()->form('post', route('selecoes.storetemplatevalue', ['selecao' => $selecao->id, 'campo' => $field]))->id('template-form-value')->open() }}
            @csrf
            @method('post')
            {{ html()->hidden('id') }}
            <div id="template-new" class="form-group row mt-2">
              <input class="form-control col-9" name="campo" type="hidden">
            </div>
            <div class="form-group row mt-2">
              <div class="col-3"><strong>Label</strong></div>
              <input class="form-control col-7" name="new[label]" id="id_campo1">
            </div>
            <div class="text-right mt-2">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button class="btn btn-primary ml-1" type="submit">Salvar</button>
            </div>
          {{ html()->form()->close() }}
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {

      $('#json-modal-form').on('shown.bs.modal', function() {
        $('#id_campo1').focus();
      });

      json_modal_form = function() {
        $('#json-modal-form').modal();
      }
    });
  </script>
@endsection
