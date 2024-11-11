@section('styles')
  @parent
  <style>
    #card-principal-conteudo {
      font-size: 1.1em !important;
    }

  </style>
@endsection

<div id="card-principal-conteudo">

  @cannot('update', $inscricao)
  <div class="card mb-1">
    <div class="card-body text-dark bg-warning py-2">
      Seu acesso à esta inscrição é somente leitura pois você não consta na lista de pessoas.
    </div>
  </div>
  @endcannot

  <span class="text-muted">Criado em:</span> {{ $inscricao->created_at->format('d/m/Y H:i') }}<br>
  <br>
</div>
