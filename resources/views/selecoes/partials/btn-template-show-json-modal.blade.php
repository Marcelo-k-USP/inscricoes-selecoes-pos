<button type="button" class="btn btn-sm btn-light text-primary" onclick="json_modal_form()">
  <i class="fas fa-copy"></i> Editar Json
</button>

<!-- Modal -->
<div class="modal fade" id="json-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalShowJson" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalShowJson">Formulário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{ html()->form('post', 'selecoes/' . $selecao->id . '/template_json')->id('jsonForm')->open() }}
            @csrf
            {{ html()->hidden('id') }}
            <style>
              #template {
                height: auto !important;
                overflow-y: auto !important;
              }
            </style>
            <div class="form-group row">
              {{ html()->label('Json')->for('template')->class('col-form-label col-sm-2') }}
              <div class="col-sm-10">
                @php
                  $value = ((is_null($selecao->template)) ? '' : json_encode(json_decode($selecao->template), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                @endphp
                {{ html()->textarea()->name('template')->value($value)->id('template')->class('form-control')->attribute('rows', '15') }}
              </div>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="validaJson()" class="btn btn-primary">Salvar</button>
            </div>
          {{ html()->form()->close() }}
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script>
    $(document).ready(function() {

        $('#json-modal-form').on('shown.bs.modal', function() {
          $('#template').focus();
        });

        json_modal_form = function() {
          $('#json-modal-form').modal();
        };
    });

    function validaJson() {
        var json = $('#template').val();
        if (json != '') {
            try {
                obj = JSON.parse(json);
            } catch (e) {
                alert('Erro: Json mal formatado!');
                alert(e);
                return;
            }
        }
        document.getElementById("jsonForm").submit();
    }
  </script>
@endsection
