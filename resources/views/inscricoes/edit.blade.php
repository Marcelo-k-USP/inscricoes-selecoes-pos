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
    $condicao_ativa = true;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-top">
          <div class="card-title my-0">
            @if ($modo == 'edit')
              <div style="display: flex; align-items: center; white-space: nowrap;">
                <a href="inscricoes">Inscrições</a> <i class="fas fa-angle-right mx-2"></i> Inscrição nº {{ $inscricao->id }}
                &nbsp; | &nbsp;
                @include('inscricoes.partials.btn-enable-disable')
              </div>
            @else
              Nova Inscrição
            @endif
            para {{ $inscricao->selecao->nome }} ({{ $inscricao->selecao->categoria->nome }})
            @if ($inscricao->selecao->categoria->nome !== 'Aluno Especial')
              - {{ $nivel }}
            @endif
            <br />
            <span class="text-muted">{{ $inscricao->selecao->descricao }}</span><br />
          </div>
        </div>
        @include('inscricoes.partials.badge-instrucoes-da-selecao')
        @include('inscricoes.partials.instrucoes-da-selecao')
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @if (!in_array($inscricao->selecao->estado, ['Aguardando Início das Solicitações de Isenção de Taxa e das Inscrições', 'Aguardando Início das Inscrições']))
                @include('inscricoes.show.card-principal', [    {{-- Principal --}}
                  'selecao' => $inscricao->selecao
                ])
              @else
                @include('inscricoes.show.card-naodisponivel')  {{-- Não Disponível --}}
              @endif
            </div>
            <div class="col-md-5">
              @if (($inscricao->selecao->categoria->nome == 'Aluno Especial') &&
                   (($modo == 'edit') || ($inscricao->selecao->estado == 'Encerrada')))
                @include('inscricoes.show.card-disciplinas')    {{-- Disciplinas --}}
              @endif
              @include('inscricoes.show.card-responsaveis', [   {{-- Responsáveis --}}
                'selecao' => $inscricao->selecao
              ])
              @include('inscricoes.show.card-informativos', [   {{-- Informativos --}}
                'selecao' => $inscricao->selecao
              ])
              @if ($modo == 'edit')
                @include('common.card-arquivos', [              {{-- Arquivos --}}
                  'selecao' => $inscricao->selecao,
                  'tipoarquivo_classe_nome_plural_acentuado' => 'Inscrições',
                ])
                @if (in_array($inscricao->selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições', 'Período de Inscrições']) && (session('perfil') == 'usuario'))
                  @include('inscricoes.show.card-envio')        {{-- Envio --}}
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
