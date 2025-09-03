$(document).ready(function() {

  $('[data-toggle="tooltip"]').tooltip();

  $('input[id^="input_arquivo_"]').change(function() {
    var i_tipoarquivo = $(this).attr('id').split('_')[2];
    submete_form('arquivos', 'post', i_tipoarquivo);
  });
});

function excluir_arquivo(arquivo_id, arquivo_nome) {
  if (confirm('Tem certeza que deseja deletar o documento ' + arquivo_nome + '?'))
    submete_form('arquivos/' + arquivo_id, 'delete');
}

function gerar_boletos(inscricao_id) {
  submete_form('inscricoes/geraboletos/' + inscricao_id, 'post');
}

function enviar_boleto(inscricao_id, arquivo_id) {
  submete_form('inscricoes/' + inscricao_id + '/enviaboleto/' + arquivo_id, 'post');
}

function submete_form(acao, metodo, i_tipoarquivo) {
  $('#tipoarquivo').val($('#tipoarquivo_' + i_tipoarquivo).val());
  $('#modal_processando').modal('show');
  $('#form_arquivos').attr({ action: acao });
  $('<input>').attr({ type: 'hidden', name: '_method', value: metodo }).appendTo('#form_arquivos');
  $('#form_arquivos').submit();
}
