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
    $condicao_nao_iniciada = (in_array($selecao->estado, ['Aguardando Documentação', 'Aguardando Início']));
    $condicao_ativa = ($selecao->estado != 'Encerrada');
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title form-inline my-0">
            @if ($modo == 'edit')
              <a href="selecoes">Seleções</a> <i class="fas fa-angle-right mx-2"></i>
              {{ $selecao->nome }}
              @if (!is_null($selecao->categoria))
                &nbsp;({{ $selecao->categoria->nome }})
              @endif
              &nbsp; | &nbsp; &nbsp;
              @include('selecoes.partials.btn-enable-disable')
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
                  @include('selecoes.show.card-linhaspesquisa')                      {{-- Linhas de Pesquisa/Temas --}}
                @else
                  @include('selecoes.show.card-disciplinas')                         {{-- Disciplinas --}}
                @endif
                @include('selecoes.show.card-motivosisencaotaxa')                    {{-- Motivos de Isenção de Taxa --}}
                @include('common.card-arquivos')                                     {{-- Arquivos --}}
                @php
                  $tipoarquivo_classe_nome_plural_acentuado = 'Solicitações de Isenção de Taxa';    // para o include abaixo
                  $tipoarquivo_classe_nome = 'SolicitacaoIsencaoTaxa';
                  $tiposarquivo = $tiposarquivo_solicitacaoisencaotaxa;
                @endphp
                @include('selecoes.show.card-tiposarquivo')                          {{-- Tipos de Arquivo nas Solicitações de Isenção de Taxa --}}
                @php
                  $tipoarquivo_classe_nome_plural_acentuado = 'Inscrições';          // para o include abaixo
                  $tipoarquivo_classe_nome = 'Inscricao';
                  $tiposarquivo = $tiposarquivo_inscricao;
                @endphp
                @include('selecoes.show.card-tiposarquivo')                          {{-- Tipos de Arquivo nas Inscrições --}}
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
