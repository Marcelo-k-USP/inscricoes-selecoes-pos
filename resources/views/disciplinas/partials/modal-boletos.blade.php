<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#DisciplinaBoletosModal">
  <i class="fas fa-plus"></i> Gerar
</button>

<!-- Modal -->
<div class="modal fade" id="DisciplinaBoletosModal" data-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Gerar Boleto(s)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{-- estamos dentro do form_arquivos, então não podemos encadear outro form aqui
               como alternativa, vamos utilizar o form_arquivos mesmo, alterando seus parâmetros (método e url) --}}
          <div class="form-group row">
            <div class="col-form-label col-sm-3">Disciplina(s)</div>
          </div>
          <div class="form-group row">
            @foreach ($inscricao->disciplinas_sem_boleto as $disciplina)
              <div class="col-sm-12 d-flex align-items-center" style="gap: 10px;">
                {{ html()->input('checkbox', 'disciplinas[' . $disciplina->sigla . ']')->checked(old('disciplinas[' . $disciplina->sigla . ']'))->class('form-control')->style('width: auto; margin: 0;') }}
                {{ html()->label($disciplina->sigla . ' - ' . $disciplina->nome, 'disciplinas[' . $disciplina->sigla . ']')->style('margin: 0;') }}
              </div>
            @endforeach
          </div>
          <div class="text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" id="btnGerarBoletos" disabled>Gerar</button>
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

      add_modal_form = function() {
        $('#DisciplinaBoletosModal').modal();
      };

      $('input[type="checkbox"][name^="disciplinas["]').on('change', function() {
        $('#btnGerarBoletos').prop('disabled', ($('input[type="checkbox"][name^="disciplinas["]').filter(':checked').length == 0));
      });

      $('#btnGerarBoletos').click(function(e) {
        e.preventDefault();
        // como não podíamos encadear um form dentro de outro, reutilizamos o form_arquivos
        $('#form_arquivos').find('input[name="_method"]').remove();
        $('#form_arquivos').attr('method', 'post');
        $('#form_arquivos').attr('action', '{{ $inclusor_url }}');
        $('#form_arquivos').submit();
      });
    });
  </script>
@endsection
