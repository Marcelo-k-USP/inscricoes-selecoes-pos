<div class="row">
  <div class="col-md-12 form-inline">
    <span class="h4 mt-2">Disciplinas</span>
    @can('disciplinas.create')
      &nbsp; &nbsp;
      <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Nova
      </button>
    @endcan
  </div>
</div>

<table class="table table-sm my-0 ml-3">
  @foreach ($disciplinas as $disciplina)
    {{-- Mostra o conteúdo de uma disciplina --}}
    <tr>
      <td>
        <div>
          <a name="{{ \Str::lower($disciplina->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $disciplina->sigla }} - {{ $disciplina->nome }}</a>
          @can('disciplinas.update')
            @include('disciplinas.partials.btn-edit')
          @endcan
          @can('disciplinas.delete')
            @include('disciplinas.partials.btn-delete')
          @endcan
          @include('disciplinas.partials.detalhes')
        </div>
      </td>
    </tr>
  @endforeach
</table>
