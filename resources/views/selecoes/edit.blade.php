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
    $selecao = $modelo;
    $tipo_modelo = 'Seleção';
    $condicao_ativa = true;
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
        <div class="card-body {{ ($modo == 'edit') && ($selecao->estado == 'Encerrada') ? 'disable-links': '' }}">
          <div class="row">
            <div class="col-md-7">
              @include('selecoes.show.card-principal')          {{-- Principal --}}
              @if ($modo == 'edit')
                @include('selecoes.show.card-formulario')       {{-- Formulario --}}
              @endif
            </div>
            <div class="col-md-5">
              @if ($modo == 'edit')
                @include('selecoes.show.card-linhaspesquisa')   {{-- Linhas de Pesquisa --}}
                @include('common.card-arquivos')                {{-- Arquivos --}}
                @include('selecoes.show.card-inscricoes')       {{-- Inscrições --}}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
