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
        if ($('#categoria_id option:selected').text() !== 'Aluno Especial')
          programa_div.show();
        else {
          $('#programa_id option:first').prop('selected', true);
          programa_div.hide();
        }
      });

      $('#categoria_id').trigger('change');

      updateCamposSolicitacoesIsencaoTaxaDataHora();
      updateCamposBoleto();

      $('#tem_taxa').on('click', function () {
        updateCamposSolicitacoesIsencaoTaxaDataHora();
        updateCamposBoleto();
      });
    });

    function updateCamposSolicitacoesIsencaoTaxaDataHora() {
      if (!$('#tem_taxa').prop('checked')) {
        $('#solicitacoesisencaotaxa_data_inicio').val('');
        $('#solicitacoesisencaotaxa_data_inicio').parents('div').eq(1).hide();
        $('#solicitacoesisencaotaxa_hora_inicio').next('.flatpickr-calendar').find('input[type="number"]').val('');
        $('#solicitacoesisencaotaxa_hora_inicio').parents('div').eq(1).hide();
        $('#solicitacoesisencaotaxa_data_fim').val('');
        $('#solicitacoesisencaotaxa_data_fim').parents('div').eq(1).hide();
        $('#solicitacoesisencaotaxa_hora_fim').next('.flatpickr-calendar').find('input[type="number"]').val('');
        $('#solicitacoesisencaotaxa_hora_fim').parents('div').eq(1).hide();
      } else {
        $('#solicitacoesisencaotaxa_data_inicio').parents('div').eq(1).show();
        $('#solicitacoesisencaotaxa_hora_inicio').parents('div').eq(1).show();
        $('#solicitacoesisencaotaxa_data_fim').parents('div').eq(1).show();
        $('#solicitacoesisencaotaxa_hora_fim').parents('div').eq(1).show();
      }
    }

    function updateCamposBoleto() {
      if (!$('#tem_taxa').prop('checked')) {
        $('#boleto_data_vencimento').val('');
        $('#boleto_data_vencimento').parents('div').eq(1).hide();
        $('#boleto_valor').val('');
        $('#boleto_valor').parents('div').eq(1).hide();
        $('#boleto_texto').val('');
        $('#boleto_texto').parents('div').eq(1).hide();
      } else {
        $('#boleto_data_vencimento').parents('div').eq(1).show();
        $('#boleto_valor').parents('div').eq(1).show();
        $('#boleto_texto').parents('div').eq(1).show();
      }
    }
  </script>
@endsection
