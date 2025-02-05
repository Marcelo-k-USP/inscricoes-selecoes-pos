@section('styles')
@parent
  <style>
    #card-niveis {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_niveis"></a>
<div class="card bg-light mb-3" id="card-niveis">
  <div class="card-header">
    Níveis
    <span class="badge badge-pill badge-primary">{{ is_null($linhapesquisa->niveis) ? 0 : $linhapesquisa->niveis->count() }}</span>
    @can('linhaspesquisa.update')
      @include('niveis.partials.modal-add', ['inclusor_url' => 'linhaspesquisa', 'inclusor_objeto' => $linhapesquisa])
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionNiveis">
      @if (!is_null($linhapesquisa->niveis))
        @foreach ($linhapesquisa->niveis as $nivel)
          <div class="card nivel-item">
            <div class="card-header" style="font-size:15px">
              @include('niveis.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
