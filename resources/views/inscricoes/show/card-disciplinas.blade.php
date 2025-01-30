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
    <span class="badge badge-pill badge-primary">{{ is_null($inscricao_disciplinas) ? 0 : count($inscricao_disciplinas) }}</span>
    @if ($condicao_ativa)
      @include('disciplinas.partials.modal-add-inscricoes')
    @endif
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionDisciplinas">
      @if (!is_null($inscricao_disciplinas))
        @foreach ($inscricao_disciplinas as $inscricao_disciplina)
          <div class="card disciplina-item">
            <div class="card-header" style="font-size:15px">
              @include('disciplinas.show.header-inscricoes')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
