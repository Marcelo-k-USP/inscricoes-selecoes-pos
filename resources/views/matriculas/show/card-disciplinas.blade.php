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
    <span class="badge badge-pill badge-primary">{{ is_null($matricula_disciplinas) ? 0 : count($matricula_disciplinas) }}</span>
    @if (in_array($matricula->selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições/Matrículas', 'Período de Inscrições/Matrículas']) && (session('perfil') == 'usuario'))
      @include('disciplinas.partials.modal-add', ['inclusor_url' => 'matriculas', 'inclusor_objeto' => $matricula])
    @endif
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionDisciplinas">
      @if (!is_null($matricula_disciplinas))
        @foreach ($matricula_disciplinas as $matricula_disciplina)
          <div class="card disciplina-item">
            <div class="card-header" style="font-size:15px">
              @include('disciplinas.show.header-matriculas')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
