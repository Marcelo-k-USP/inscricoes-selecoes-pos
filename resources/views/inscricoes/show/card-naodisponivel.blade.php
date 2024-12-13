@section('styles')
@parent
  <style>
    #card-inscricao-naodisponivel {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

<div class="card mb-3 w-100" id="card-inscricao-naodisponivel">
  <div class="card-header">
    Informações básicas
  </div>
  <div class="card-body">
    <div class="list_table_div_form">
      As inscrições para este processo seletivo ainda não abriram.
    </div>
  </div>
</div>
