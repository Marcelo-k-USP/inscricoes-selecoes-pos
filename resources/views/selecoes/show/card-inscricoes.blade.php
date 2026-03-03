@section('styles')
@parent
  <style>
    #card-selecao-inscricoes {
      border: 1px solid blue;
      border-top: 3px solid blue;
    }
  </style>
@endsection

@nomenclatura

<div class="card mb-3" id="card-selecao-inscricoes">
  <div class="card-header">
    <i class="fas fa-chart-line"></i> {{ ucfirst($inscricao_ou_matricula_plural) }}
  </div>
  <div class="card-body">
    <ul class="list-unstyled">
      <li>Contagem (sujeito a exclusão de dados antigos)
          @include('selecoes.partials.inscricoes-por-ano')
      </li>
    </ul>
  </div>
</div>
