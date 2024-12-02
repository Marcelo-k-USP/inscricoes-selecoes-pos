<div class="row">
  <div class="col-md-12 form-inline">
    <span class="h4 mt-2">Usuários Locais</span>
    &nbsp;
    @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    &nbsp;
    <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
      <i class="fas fa-plus"></i> Novo
    </button>
  </div>
</div>

<table class="table table-sm my-0 ml-3">
  @foreach ($localusers as $localuser)
    {{-- Mostra os dados de um usuário local --}}
    <tr>
      <td>
        <div>
          <a name="{{ \Str::lower($localuser->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $localuser->nome }}</a>
          @can('perfiladmin')
            @include('localusers.partials.modal-edit')
            @include('localusers.partials.btn-delete')
          @endcan
        </div>
      </td>
    </tr>
  @endforeach
</table>
