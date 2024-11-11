@extends('master')

@section('title', '#' . $inscricao->id)

@section('styles')
  @parent
  <style>
    #card-principal {
      border: 1px solid blue;
    }

    .bg-principal {
      background-color: LightBlue;
      border-top: 3px solid blue;
    }

    .disable-links {
      pointer-events: none;
    }

  </style>
@endsection

@section('content')
  @parent

  <div class="card bg-light mb-3" id="card-principal">
    <div class="card-header bg-principal form-inline">
      <div class="mr-auto">
        <span class="text-muted">Inscrição no.</span> {{ $inscricao->id }}
        <span class="text-muted">para</span> ({{ $inscricao->selecao->processo->nome }}) {{ $inscricao->selecao->nome }}
        @include('inscricoes.partials.instrucoes-da-selecao-badge')
        <div class="small ml-3">{{ $inscricao->selecao->descricao }}</div>
      </div>
    </div>
    <div class="card-body">
      @include('inscricoes.partials.instrucoes-da-selecao')
      <div class="row">
        <div class="col-md-8">
          {{-- Informações principais --}}
          @include('inscricoes.show.principal')
        </div>
      </div>
    </div>
  </div>
@endsection
