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
    $solicitacaoisencaotaxa = $objeto;
    $inscricao = $solicitacaoisencaotaxa;    // necessário porque fazemos include de alguns blades de inscrições, que referenciam $inscricao ao invés de $solicitacaoisencaotaxa
    $classe_nome = 'SolicitacaoIsencaoTaxa';
    $condicao_disponivel = ($solicitacaoisencaotaxa->selecao->estado == 'Em Andamento');
    $condicao_ativa = true;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-top">
          <div class="card-title my-0">
            @if ($modo == 'edit')
              <div>
                <a href="solicitacoesisencaotaxa">Solicitações de Isenção de Taxa</a>
                &nbsp; | &nbsp;
                @include('inscricoes.partials.btn-enable-disable')
              </div>
            @else
              Nova Solicitação de Isenção de Taxa
            @endif
            para {{ $solicitacaoisencaotaxa->selecao->nome }} ({{ $solicitacaoisencaotaxa->selecao->categoria->nome }})<br />
            <span class="text-muted">{{ $solicitacaoisencaotaxa->selecao->descricao }}</span>
          </div>
        </div>
        @include('inscricoes.partials.badge-instrucoes-da-selecao')
        @include('solicitacoesisencaotaxa.partials.instrucoes-da-selecao')
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @if ($condicao_disponivel)
                @include('solicitacoesisencaotaxa.show.card-principal')     {{-- Principal --}}
              @else
                @include('inscricoes.show.card-naodisponivel')              {{-- Não Disponível --}}
              @endif
            </div>
            <div class="col-md-5">
              @include('inscricoes.show.card-informativos')                 {{-- Informativos --}}
              @if ($condicao_disponivel && ($modo == 'edit'))
                @include('common.card-arquivos')                            {{-- Arquivos --}}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
