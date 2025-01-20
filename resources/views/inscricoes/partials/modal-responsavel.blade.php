<!-- Modal que atende exibir dados de responsáveis -->
<div class="modal fade" id="modalData" data-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Responsável</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          <div class="form-group row">
            <label class="col-form-label col-sm-3" for="nome">Nome</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="nome" id="nome" disabled>
            </div>
          </div>
          <div class="form-group row" id="row-telefone">
            <label class="col-form-label col-sm-3" for="telefone">Telefone</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="telefone" id="telefone" disabled>
            </div>
          </div>
          <div class="text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {

      open_responsavel = function(id, funcao, programa_id = null) {
        $.get('responsaveis/' + id + '/' + encodeURIComponent(funcao) + '/' + (programa_id !== null ? programa_id : '')
          , function(row) {
            console.log(row);

            // preenchendo o modal com os valores a serem exibidos
            $('#nome').val(row['name']);
            $('#telefone').val(row['telefone']);

            if (funcao !== 'Secretários(as) dos Programas')
              $('#row-telefone').hide();
            else
              $('#row-telefone').show();

            $("#modalData").modal();
          });
      };
    });
  </script>
@endsection
