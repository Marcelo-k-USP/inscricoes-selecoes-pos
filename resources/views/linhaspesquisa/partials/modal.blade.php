<!-- Modal que atende adicionar e editar linhas de pesquisa -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adicionar/Editar linhas de pesquisa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">
                    {{ html()->form('post', '')->open() }}
                        @csrf
                        @method('POST')
                        {{ html()->hidden('id') }}
                        @php
                            $modo = 'create';
                        @endphp
                        @foreach ($fields as $col)
                            @if (Str::startsWith($col['name'], 'codpes_'))
                                @include('common.list-table-form-pessoa')
                            @elseif (empty($col['type']) || $col['type'] == 'text')
                                @include('common.list-table-form-text')
                            @elseif ($col['type'] == 'select')
                                @include('common.list-table-form-select')
                            @endif
                        @endforeach
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
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

        var modalForm = $('#modalForm');
        var $oSelect2 = modalForm.find(':input[name^="codpes_"]');

        $oSelect2.select2({
            ajax: {
                url: params => 'search/' + ($.isNumeric(params.term) ? 'codpes' : 'partenome'),
                dataType: 'json',
                delay: 1000
            },
            dropdownParent: modalForm,
            minimumInputLength: 4,
            theme: 'bootstrap4',
            width: '100%',
            language: 'pt_br'
        });
        
        $('#modalForm').on('shown.bs.modal', function() {
            $(this).find(':input[type=text]').filter(':visible:first').focus();
        })

        // coloca o focus no select2
        // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        
        edit_form = function(id) {
            $.get('linhaspesquisa/' + id, function(row) {
                console.log(row);
                // mudando para PUT
                $('#modalForm :input').filter("input[name='_method']").val('PUT');

                // preenchendo o form com os valores a serem editados
                var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
                inputs.each(function() {
                    $(this).val(row[this.name]);
                    if (this.name.startsWith('codpes_')) {
                        // adiciona o valor nas options do select
                        var valor_full = row[this.name];
                        var valor_json = JSON.parse(valor_full.substring(valor_full.indexOf('{'), valor_full.lastIndexOf('}') + 1));
                        var valor = valor_json.results[0].text;
                        $(this).append(new Option(valor, valor.split(' ')[0], true, true)).trigger('change');
                    }
                    console.log(this.name);
                });

                // Ajustando action
                $('#modalForm').find('form').attr('action', 'linhaspesquisa/' + id);

                // Ajustando o title
                $('#modalLabel').html('Editar Linha de Pesquisa');

                $("#modalForm").modal();
                console.log('inputs', inputs);
            });
        }

        add_form = function() {
            $("#modalForm :input").filter("input[type='text']").val('');
            $('#modalForm select').empty().val(null).trigger('change');

            // Ajustando action
            $('#modalForm').find('form').attr('action', 'linhaspesquisa');

            $('#modalLabel').html('Adicionar Linha de Pesquisa');
            $('#modalForm :input').filter("input[name='_method']").val('POST');

            $("#modalForm").modal();
        }
    })
</script>
@endsection
