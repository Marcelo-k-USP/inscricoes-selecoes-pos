<div class="row">
  <div class="col-md-12 form-inline">
    <span class="h4 mt-2">Motivos de Isenção de Taxa</span>
    @can('create', App\Models\MotivoIsencaoTaxa::class)
      &nbsp; &nbsp;
      <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Novo
      </button>
      @endcan
  </div>
</div>

<table class="table table-sm my-0 ml-3">
  @foreach ($motivosisencaotaxa as $motivoisencaotaxa)
    {{-- Mostra o conteúdo de um motivo de isenção de taxa --}}
    <tr>
      <td>
        <div>
          <a name="{{ \Str::lower($motivoisencaotaxa->id) }}" class="font-weight-bold" style="text-decoration: none;">{{ $motivoisencaotaxa->nome }}</a>
          @can('update', App\Models\MotivoIsencaoTaxa::class)
            @include('motivosisencaotaxa.partials.btn-edit')
          @endcan
          @can('delete', App\Models\MotivoIsencaoTaxa::class)
            @include('motivosisencaotaxa.partials.btn-delete')
          @endcan
          @include('motivosisencaotaxa.partials.detalhes')
        </div>
      </td>
    </tr>
  @endforeach
</table>
