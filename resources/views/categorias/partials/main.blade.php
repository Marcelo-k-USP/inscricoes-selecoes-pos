<div class="row">
  <div class="col-md-12 form-inline">
    <span class="h4 mt-2">Categorias</span>
    @can('categorias.create')
      &nbsp; &nbsp;
      <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Nova
      </button>
    @endcan
  </div>
</div>

<table class="table table-sm my-0 ml-3">
  @foreach ($categorias as $categoria)
    {{-- Mostra o conteúdo de uma categoria --}}
    <tr>
      <td>
        <div>
          <a name="{{ \Str::lower($categoria->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $categoria->nome }}</a>
          @can('categorias.update')
            @include('categorias.partials.btn-edit')
          @endcan
          @can('categorias.delete')
            @include('categorias.partials.btn-delete')
          @endcan
          @include('categorias.partials.detalhes')
        </div>
      </td>
    </tr>
  @endforeach
</table>
