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
    $classe_nome = 'SolicitacaoIsencaoTaxa';
    $condicao_ativa = true;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-top">
          <div class="card-title my-0">
            @if ($modo == 'edit')
              <div style="display: flex; align-items: center; white-space: nowrap;">
                <a href="solicitacoesisencaotaxa">Solicitações de Isenção de Taxa</a>
                &nbsp; | &nbsp;
                @include('solicitacoesisencaotaxa.partials.btn-enable-disable')
              </div>
            @else
              Nova Solicitação de Isenção de Taxa
            @endif
            para {{ $solicitacaoisencaotaxa->selecao->nome }} ({{ $solicitacaoisencaotaxa->selecao->categoria->nome }})<br />
            <span class="text-muted">{{ $solicitacaoisencaotaxa->selecao->descricao }}</span>
          </div>
        </div>
        @include('solicitacoesisencaotaxa.partials.badge-instrucoes-da-selecao')
        @include('solicitacoesisencaotaxa.partials.instrucoes-da-selecao')
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @if (in_array($solicitacaoisencaotaxa->selecao->estado, ['Período de Solicitações de Isenção', 'Período de Inscrições', 'Encerrada']))
                @include('solicitacoesisencaotaxa.show.card-principal', [    {{-- Principal --}}
                  'selecao' => $solicitacaoisencaotaxa->selecao
                ])
              @else
                @include('solicitacoesisencaotaxa.show.card-naodisponivel')  {{-- Não Disponível --}}
              @endif
            </div>
            <div class="col-md-5">
              @include('inscricoes.show.card-responsaveis', [                {{-- Responsáveis --}}
                'selecao' => $solicitacaoisencaotaxa->selecao
              ])
              @include('inscricoes.show.card-informativos', [                {{-- Informativos --}}
                'selecao' => $solicitacaoisencaotaxa->selecao
              ])
              @if ($modo == 'edit')
                @include('common.card-arquivos', [                           {{-- Arquivos --}}
                  'selecao' => $solicitacaoisencaotaxa->selecao,
                  'tipoarquivo_classe_nome_plural_acentuado' => 'Solicitações de Isenção de Taxa',
                ])
                @if (($solicitacaoisencaotaxa->selecao->estado == 'Período de Solicitações de Isenção') && (session('perfil') == 'usuario'))
                  @include('inscricoes.show.card-envio')                     {{-- Envio --}}
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@if (isset($scroll))
  @section('javascripts_bottom')
  @parent
    <script type="text/javascript">
      $(document).ready(function() {

        var element = $('a[name="card_{{ $scroll }}"]');
        if (element.length)
          element.get(0).scrollIntoView({ behavior: 'smooth' });
      });
    </script>
  @endsection
@endif
