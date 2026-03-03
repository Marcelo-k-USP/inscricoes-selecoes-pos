@section('styles')
@parent
  <style>
    #card-categorias {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_categorias"></a>
<div class="card bg-light mb-3" id="card-categorias">
  <div class="card-header">
    Categorias
    <span class="badge badge-pill badge-primary">{{ is_null($tipoarquivo->categorias) ? 0 : $tipoarquivo->categorias->count() }}</span>
    @can('tiposarquivo.update')
      @include('categorias.partials.modal-add', ['inclusor_url' => 'tiposarquivo', 'inclusor_objeto' => $tipoarquivo])
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionCategorias">
      @if (!is_null($tipoarquivo->categorias))
        @foreach ($tipoarquivo->categorias as $categoria)
          <div class="card categoria-item">
            <div class="card-header" style="font-size:15px">
              @include('categorias.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
