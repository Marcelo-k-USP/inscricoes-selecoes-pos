@extends('master')

@section('title', 'Nova Inscrição')

@section('content_header')
@stop

@section('javascripts_bottom')
  @parent
@stop

@section('content')
  @parent

  <div class="card bg-light mb-3">
    <div class="card-header">
      <span class="text-muted">Nova inscrição para</span> {{ $selecao->nome }}
      @include('inscricoes.partials.instrucoes-da-selecao-badge')
      <div class="small ml-3">{{ $selecao->descricao }}</div>
    </div>
    <div class="card-body">

      @include('inscricoes.partials.instrucoes-da-selecao')

      <form method="POST" role="form" action="{{ route('inscricoes.store', $selecao->id) }}">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="autor" class="control-label">Por</label>
              <input class="form-control" type="text" name="autor" value="{{ \Auth::user()->name }}" disabled>
              <br>
            </div>
          </div>

          {{-- renderiza o formulario personalizado da seleção --}}
          <div class="col-md-6">
            <div class="form-group">
            </div>
          </div>

        </div>
        <div class="form-group card-header">
          <button type="button" class="btn btn-secondary" onclick="window.history.back();"><i
              class="fas fa-times-circle"></i> Cancelar</button>
          <button type="submit" class="btn btn-primary">Enviar <i class="fas fa-check-circle"></i></button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('javascripts_bottom')
    @parent
    <script src="{{ asset('js/functions.js') }}" type="text/javascript">
      /* @autor uspdev/alecosta 10/02/2022
       * Ao carregar a página ordena todos os campos caixa de seleção adicionados na fila
      */
      $(document).ready(function() {
        // Pega todos os campos extras que são caixa de seleção
        $('select[name^="extras"]').each(function () {
          var nameField = $(this).prop('name');
          // Considerando que todo campo adicionado na fila tenha o nome padrão extras[alguma-coisa]
          var start = 7;
          var end = nameField.length - 1;
          // Ordena as opções do campo caixa de seleção
          $(ordenarOpcoes(nameField.substring(start, end)));
        });
      });
  </script>
@endsection
