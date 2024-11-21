<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Linhas de Pesquisa</span>
        &nbsp; &nbsp;
        <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
            <i class="fas fa-plus"></i> Nova
        </button>
    </div>
</div>

<table class="table table-sm my-0 ml-3">
    @foreach ($linhaspesquisa as $linhapesquisa)
        {{-- Mostra o conteúdo de uma linha de pesquisa --}}
        <tr>
            <td>
                <div>
                    <a name="{{ \Str::lower($linhapesquisa->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $linhapesquisa->nome }}</a>
                    @can('perfiladmin')
                        @include('linhaspesquisa.partials.edit-modal')
                        @include('linhaspesquisa.partials.btn-delete')
                    @endcan
                    @include('linhaspesquisa.partials.detalhes')
                </div>
            </td>
        </tr>
    @endforeach
</table>
