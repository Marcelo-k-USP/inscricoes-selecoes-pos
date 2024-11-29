@extends('master')

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

  @php
    $inscricao = $modelo;
    $tipo_modelo = 'Inscrição';
    $condicao_ativa = true;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title my-0">
            @if ($modo == 'edit')
              <a href="inscricoes">Inscrições</a> <i class="fas fa-angle-right mx-2"></i> Inscrição nº {{ $inscricao->id }}
            @else
              Nova Inscrição
            @endif
            para {{ $inscricao->selecao->nome }} ({{ $inscricao->selecao->categoria->nome }})<br />
            <span class="text-muted">{{ $inscricao->selecao->descricao }}</span><br />
          </div>
        </div>
        @include('inscricoes.partials.badge-instrucoes-da-selecao')
        @include('inscricoes.partials.instrucoes-da-selecao')
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @include('inscricoes.show.card-principal')    {{-- Principal --}}
            </div>
            <div class="col-md-5">
              @include('inscricoes.show.card-informativos') {{-- Informativos --}}
              @if ($modo == 'edit')
                @include('common.card-arquivos')            {{-- Arquivos --}}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
