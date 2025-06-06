<div class="row">
  <div class="col-md-12 form-inline">
    <span class="h4 mt-2">Programas</span>
    @can('programas.create')
      &nbsp; &nbsp;
      <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Novo
      </button>
    @endcan
  </div>
</div>

<table class="table table-sm my-0 ml-3">
  @foreach ($programas as $programa)
    {{-- Mostra o conteúdo de um programa --}}
    <tr>
      <td>
        <div>
          <a name="{{ \Str::lower($programa->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $programa->nome }}</a>
          @can('programas.update')
            @include('programas.partials.btn-edit')
          @endcan
          @can('programas.delete')
            @include('programas.partials.btn-delete')
          @endcan
          @include('programas.partials.detalhes')
        </div>
      </td>
    </tr>
  @endforeach
</table>
