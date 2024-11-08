<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Processos</span>
        &nbsp; &nbsp;
        <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
            <i class="fas fa-plus"></i> Novo
        </button>
    </div>
</div>

<table class="table table-sm my-0 ml-3">
    @foreach ($processos as $processo)
        {{-- Mostra o conteúdo de um processo --}}
        <tr>
            <td>
                <div>
                    <a name="{{ \Str::lower($processo->id) }}" class="font-weight-bold">{{ $processo->nome }}</a>
                    @can('perfiladmin')
                        @include('processos.partials.edit-modal')
                        @include('processos.partials.btn-delete')
                    @endcan
                    @include('processos.partials.detalhes')
                </div>
            </td>
        </tr>
    @endforeach
</table>
