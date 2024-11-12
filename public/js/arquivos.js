$(document).ready(function() {
    //ativa os tooltips do bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    $("#btn_adicionar").click(function(e) {
        if ($('#tipo_arquivo').prop('selectedIndex') === 0) {
            e.preventDefault();
            alert("Por favor, selecione um tipo de arquivo.");
        }
    });

    $("#input_arquivo").change(function() {
        var file_name = $(this).val().split(/(\\|\/)/g).pop();
        if (file_name.length == 0) {
            $("#nome_arquivo").fadeOut();
            $("#nome_arquivo p").text('');
        } else {
            var files = $("#input_arquivo")[0].files;
            var file_type;
            for (var i = 0; i < files.length; i++)
            {
                if (files[i].size / 1024 / 1024 > maxUploadSize)
                    window.alert('O arquivo ultrapassa o tamanho máximo permitido de ' + maxUploadSize + 'MB');
                else {
                    file_name = files[i].name;
                    file_type = $('#tipo_arquivo option:selected').text();
                    $("#nome_arquivo ul").append(
                        '<li title="(' + (files[i].size / 1024).toFixed(2) + 'KB)"><span id="' + i + '" class="btn text-danger btn-sm"> <i class="fas fa-times"></i></span>' + file_type + ': ' + file_name + '</li>'
                    );
                }
            }
            $("#nome_arquivo").fadeIn();
            $("#nome_arquivo ul li span").click(remove);
        }
    });

    $('#tipo_arquivo').change(function() {
        if ($('#tipo_arquivo').prop('selectedIndex') === 0) {
            $("#btn_adicionar").addClass('disabled');
        } else {
            $("#btn_adicionar").removeClass('disabled');
        }
    });

    $("#limpar_input_arquivo").click(function() {
        $("#input_arquivo").val('');
        $("#nome_arquivo").fadeOut();
        $("#nome_arquivo ul").text('');
        $("#input_arquivo")[0].files = new FileListItems([]);
        $("#btn_salvar").removeClass('disabled');
        
    });

    $(".btn-editar.btn-arquivo-acao, .limpar-edicao-nome").click(function() {
        $(this).parent().parent().parent().toggleClass('modo-edicao');
        $(this).parent().parent().parent().toggleClass('modo-visualizacao');
    });
});

function remove(){
    var index = $(this).attr('id');
    var files = Array.from($("#input_arquivo")[0].files);
    files.splice(index, 1);
    var fileList = new FileListItems(files);
    $("#input_arquivo")[0].files = fileList;
    $(this).parent().remove();
    $("#btn_salvar").removeClass('disabled');
    for (var i = 0; i < fileList.length; i++)
        if (files[i].size / 1024 / 1024 > maxUploadSize)
            $("#btn_salvar").addClass('disabled');
    $('.nome-arquivo .preview-files li span').each(function(index) {
        $(this).attr('id', index);
    });
}

function FileListItems (files) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer();
    for (var i = 0, len = files.length; i<len; i++)
        b.items.add(files[i]);
    return b.files;
}

function ativar_exclusao() {
    $(".deletar-imagem-btn").click(function() {
        var arquivo_id = $(this).attr('data-id');
        if (confirm("Tem certeza que deseja remover a imagem?"))
            $('.deletar-imagem-' + arquivo_id).submit();
    });
}
