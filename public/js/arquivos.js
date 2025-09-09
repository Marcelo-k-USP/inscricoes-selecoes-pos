$(document).ready(function() {

  $('[data-toggle="tooltip"]').tooltip();

  $('input[id^="input_arquivo_"]').change(function() {
    var i_tipoarquivo = $(this).attr('id').split('_')[2];
    submete_form('arquivos', 'post', i_tipoarquivo);
  });
});

function baixar_todos_arquivos(url_zip, url_download, too_many_files = false) {
  $('#modal_processando').modal('show');

  // inicia geração do zip
  $('#processando-mensagem').html('Gerando o arquivo zip... Aguarde.' + (too_many_files ? '<br />Dependendo da quantidade de documentos, este processo pode demorar.' : ''));
  $.ajax({
    url: url_zip,
    method: 'GET',
    success: function(data) {
      if (data.status === 'concluído') {

        // inicia o download do zip usando um link oculto
        $('#processando-mensagem').html('Baixando o arquivo zip... Aguarde.' + (too_many_files ? '<br />Dependendo da quantidade de documentos, este processo pode demorar.' : ''));

        let a = $('<a>', { href: url_download + '?zip_name=' + data.zip_name, style: 'display:none' });
        a.appendTo('body');
        a[0].click();
        a.remove();

        setTimeout(function() {
          $('#modal_processando').modal('hide');    // infelizmente, temos que fechar o download já agora, não dá pra fechar ao término do download pois não há como detectar quando o download termina
        }, 2000);    // espera 2 segundos após o download iniciar para fechar o modal, para dar tempo de ler a mensagem "Baixando o arquivo zip... Aguarde."
      } else {
        $('#processando-mensagem').html(data.mensagem);
      }
    },
    error: function() {
      $('#modal_processando .modal-body').html('Ocorreu um erro ao iniciar a geração do arquivo.<br />Por favor, tente novamente.');
    }
  });
}

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
