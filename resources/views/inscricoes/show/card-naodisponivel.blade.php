@section('styles')
@parent
  <style>
    #card-inscricao-naodisponivel {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

@nomenclatura(['selecao' => $inscricao->selecao])

<div class="card mb-3 w-100" id="card-inscricao-naodisponivel">
  <div class="card-header">
    Informações básicas
  </div>
  <div class="card-body">
    <div class="list_table_div_form">
      As {{ $inscricao_ou_matricula_plural }} para {{ $objetivo }} não estão abertas.<br />
      Ao lado/abaixo, você pode acessar os informativos do processo.
    </div>
  </div>
</div>
