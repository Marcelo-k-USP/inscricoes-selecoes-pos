@section('styles')
@parent
  <style>
    #card-orientadores {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_orientadores"></a>
<div class="card bg-light mb-3" id="card-orientadores">
  <div class="card-header">
    Orientadores
    <span class="badge badge-pill badge-primary">{{ is_null($linhapesquisa->orientadores) ? 0 : $linhapesquisa->orientadores->count() }}</span>
    @can('linhaspesquisa.update')
      @include('orientadores.partials.modal-add')
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionOrientadores">
      @if (!is_null($linhapesquisa->orientadores))
        @foreach ($linhapesquisa->orientadores as $orientador)
          <div class="card orientador-item">
            <div class="card-header" style="font-size:15px">
              @include('orientadores.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
