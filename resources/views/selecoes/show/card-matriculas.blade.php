@section('styles')
@parent
  <style>
    #card-selecao-matriculas {
      border: 1px solid blue;
      border-top: 3px solid blue;
    }
  </style>
@endsection

@nomenclatura

<div class="card mb-3" id="card-selecao-matriculas">
  <div class="card-header">
    <i class="fas fa-chart-line"></i> Matrículas
  </div>
  <div class="card-body">
    <ul class="list-unstyled">
      <li>Contagem (sujeito a exclusão de dados antigos)
          @include('selecoes.partials.matriculas-por-ano')
      </li>
    </ul>
  </div>
</div>
