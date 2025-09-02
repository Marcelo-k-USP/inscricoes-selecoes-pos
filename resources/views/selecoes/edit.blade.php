@extends('master')

@section('styles')
@parent
<style>
  .disable-links {
    pointer-events: none;
  }
</style>
@endsection

@section('content')
@parent
  @php
    $selecao = $objeto;
    $classe_nome = 'Selecao';
    $condicao_ativa = ($selecao->estado != 'Encerrada');
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title form-inline my-0">
            @if ($modo == 'edit')
              <div style="display: flex; align-items: center; white-space: nowrap;">
                <a href="selecoes">Seleções</a> <i class="fas fa-angle-right mx-2"></i> Seleção nº {{ $selecao->id }}
                &nbsp; | &nbsp;
                @include('selecoes.partials.btn-enable-disable')
              </div>
            @else
              Nova Seleção
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @include('selecoes.show.card-principal')                               {{-- Principal --}}
              @if ($modo == 'edit')
                @include('selecoes.show.card-formulario')                            {{-- Formulario --}}
              @endif
            </div>
            <div class="col-md-5">
              @if ($modo == 'edit')
                @if ($selecao->categoria->nome !== 'Aluno Especial')
                  @include('selecoes.show.card-niveislinhaspesquisa')                {{-- Níveis + Linhas de Pesquisa/Temas --}}
                @else
                  @include('selecoes.show.card-disciplinas')                         {{-- Disciplinas --}}
                @endif
                @include('selecoes.show.card-motivosisencaotaxa')                    {{-- Motivos de Isenção de Taxa --}}
                @include('common.card-arquivos', [                                   {{-- Arquivos --}}
                  'tipoarquivo_classe_nome_plural_acentuado' => 'Seleções',
                ])
                @include('selecoes.show.card-tiposarquivo', [                        {{-- Tipos de Arquivo nas Solicitações de Isenção de Taxa --}}
                  'tipoarquivo_classe_nome_plural_acentuado' => 'Solicitações de Isenção de Taxa',
                  'tipoarquivo_classe_nome' => 'SolicitacaoIsencaoTaxa',
                  'tiposarquivo' => $tiposarquivo_solicitacaoisencaotaxa
                ])
                @include('selecoes.show.card-tiposarquivo', [                        {{-- Tipos de Arquivo nas Inscrições --}}
                  'tipoarquivo_classe_nome_plural_acentuado' => 'Inscrições',
                  'tipoarquivo_classe_nome' => 'Inscricao',
                  'tiposarquivo' => $tiposarquivo_inscricao
                ])
                @include('selecoes.show.card-solicitacoesisencaotaxa')               {{-- Solicitações de Isenção de Taxa --}}
                @include('selecoes.show.card-inscricoes')                            {{-- Inscrições --}}
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
