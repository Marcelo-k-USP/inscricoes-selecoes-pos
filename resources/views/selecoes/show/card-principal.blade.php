@section('styles')
@parent
<style>
  #card-selecao-principal {
    border: 1px solid coral;
    border-top: 3px solid coral;
  }
</style>
@endsection

{{ html()->form('post', $data->url . (($modo == 'edit') ? ('/edit/' . $selecao->id) : '/create'))
  ->attribute('id', 'form_principal')
  ->open() }}
  @csrf
  @method($modo == 'edit' ? 'put' : 'post')
  {{ html()->hidden('id') }}
  <div class="card mb-3 w-100" id="card-selecao-principal">
    <div class="card-header">
      Informações básicas
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        @include('common.list-table-form-contents')
      </div>
      @if ($condicao_ativa)
        <div class="text-right">
          <button type="submit" class="btn btn-primary">{{ ($modo == 'edit' ) ? 'Salvar' : 'Prosseguir' }}</button>
        </div>
      @endif
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {
      $('#form_principal').find(':input:visible:first').focus();

      $('#categoria_id').change(function () {
        var programa_div = $('#programa_id').closest('.form-group');
        if ($('#categoria_id option:selected').text() !== 'Aluno Especial') {
          programa_div.show();
        } else {
          $('#programa_id option:first').prop('selected', true);
          programa_div.hide();
        }
      });

      $('#categoria_id').trigger('change');
    });
  </script>
@endsection
