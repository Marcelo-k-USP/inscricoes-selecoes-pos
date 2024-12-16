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
    $inscricao = $objeto;
    $classe_nome = 'Inscricao';
    $condicao_disponivel = ($inscricao->selecao->estado == 'Em andamento');
    $condicao_ativa = true;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-top">
          <div class="card-title my-0">
            @if ($modo == 'edit')
              <div>
                <a href="inscricoes">Inscrições</a> <i class="fas fa-angle-right mx-2"></i> Inscrição nº {{ $inscricao->id }}
              </div>
            @else
              Nova Inscrição
            @endif
            para {{ $inscricao->selecao->nome }} ({{ $inscricao->selecao->categoria->nome }})<br />
            <span class="text-muted">{{ $inscricao->selecao->descricao }}</span>
          </div>
          @if ($modo == 'edit')
            <div class="ml-auto">
              Situação: {{ $inscricao->estado }}
            </div>
          @endif
        </div>
        @include('inscricoes.partials.badge-instrucoes-da-selecao')
        @include('inscricoes.partials.instrucoes-da-selecao')
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @if ($condicao_disponivel)
                @include('inscricoes.show.card-principal')      {{-- Principal --}}
              @else
                @include('inscricoes.show.card-naodisponivel')  {{-- Não Disponível --}}
              @endif
            </div>
            <div class="col-md-5">
              @include('inscricoes.show.card-informativos')     {{-- Informativos --}}
              @if ($condicao_disponivel && ($modo == 'edit'))
                @include('common.card-arquivos')                {{-- Arquivos --}}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
