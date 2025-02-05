@section('styles')
@parent
  <style>
    #card-disciplinas {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_disciplinas"></a>
<div class="card bg-light mb-3" id="card-disciplinas">
  <div class="card-header">
    Disciplinas
    <span class="badge badge-pill badge-primary">{{ is_null($selecao->disciplinas) ? 0 : $selecao->disciplinas->count() }}</span>
    @can('selecoes.update', $selecao)
      @if ($condicao_ativa)
        @php
          $inclusor_url = 'selecoes';    // para o include abaixo
          $inclusor_objeto = $selecao;
        @endphp
        @include('disciplinas.partials.modal-add')
      @endif
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionDisciplinas">
      @if (!is_null($selecao->disciplinas))
        @foreach ($selecao->disciplinas as $disciplina)
          <div class="card disciplina-item">
            <div class="card-header" style="font-size:15px">
              @include('disciplinas.show.header-selecoes')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
