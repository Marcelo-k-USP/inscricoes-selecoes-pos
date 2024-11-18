$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

    $('input[id^="input_arquivo_"]').change(function() {
        $('#modal_processando').modal('show');
        $('#form_arquivo_' + $(this).attr('id').split('_')[2]).submit();
    });

    $('.btn-editar.btn-arquivo-acao, .limpar-edicao-nome').click(function() {
        $(this).parent().parent().parent().toggleClass('modo-edicao');
        $(this).parent().parent().parent().toggleClass('modo-visualizacao');
    });
});

function ativar_exclusao_arquivo(arquivo) {
    if (confirm('Tem certeza que deseja deletar ' + arquivo + '?')) {
        $('#modal_processando').modal('show');
        return true;
    } else
        return false;
}

function ativar_alteracao_arquivo() {
    $('#modal_processando').modal('show');
    return true;
}
