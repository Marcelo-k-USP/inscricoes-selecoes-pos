@section('styles')
@parent
  <style>
    #card-niveisprogramas {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_niveisprogramas"></a>
<div class="card bg-light mb-3" id="card-niveisprogramas">
  <div class="card-header">
    Combinações Níveis com Programas
    <span class="badge badge-pill badge-primary">{{ is_null($tipoarquivo->niveisprogramas) ? 0 : $tipoarquivo->niveisprogramas->count() }}</span>
    @can('tiposarquivo.update')
      @include('niveisprogramas.partials.modal-add', ['inclusor_url' => 'tiposarquivo', 'inclusor_objeto' => $tipoarquivo])
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionNiveisProgramas">
      @if (!is_null($tipoarquivo->niveisprogramas))
        @foreach ($tipoarquivo->niveisprogramas as $nivelprograma)
          <div class="card nivelprograma-item">
            <div class="card-header" style="font-size:15px">
              @include('niveisprogramas.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
