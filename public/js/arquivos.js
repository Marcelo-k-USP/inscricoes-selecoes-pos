$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

    $('[id^=input_arquivo_]').change(function() {
        var i_tipo = get_i_tipo(this);
        var file_name = $(this).val().split(/(\\|\/)/g).pop();
        if (file_name.length == 0) {
            $('#nome_arquivo_' + i_tipo).fadeOut();
            $('#nome_arquivo_' + i_tipo + " p").text('');
        } else {
            var files = $('#input_arquivo_' + i_tipo)[0].files;
            var file_type;
            for (var i = 0; i < files.length; i++)
                if (files[i].size / 1024 / 1024 > max_upload_size)
                    window.alert('O arquivo ultrapassa o tamanho máximo permitido de ' + max_upload_size + 'MB');
                else {
                    file_name = files[i].name;
                    file_type = $('#tipo_arquivo_' + i_tipo).text();
                    $('#nome_arquivo_' + i_tipo + ' ul').append(
                        '<li title="(' + (files[i].size / 1024).toFixed(2) + 'KB)"><span id="' + i_tipo + '_' + i + '" class="btn text-danger btn-sm"> <i class="fas fa-times"></i></span>' + file_name + '</li>'
                    );
                    $('#nome_arquivo_' + i_tipo + ' ul').css('margin', (i_tipo == (count_tipos_arquivo - 1)) ? '0' : '');
                }
            $('#nome_arquivo_' + i_tipo).fadeIn();
            $('#nome_arquivo_' + i_tipo + ' ul li span').click(remove);
        }
    });
});

function get_i_tipo(element) {
    return $(element).attr('id').split('_')[2];
}

function remove() {
    var index = $(this).attr('id');
    var i_tipo = index.split('_')[0];
    var files = Array.from($('#input_arquivo_' + i_tipo)[0].files);
    files.splice(index, 1);
    var fileList = new FileListItems(files);
    $('#input_arquivo_' + i_tipo)[0].files = fileList;
    
    $(this).parent().remove();
}

function FileListItems (files) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer();
    for (var i = 0, len = files.length; i < len; i++)
        b.items.add(files[i]);
    return b.files;
}
