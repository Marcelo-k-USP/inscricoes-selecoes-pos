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
            para {{ $inscricao->selecao->nome }} ({{ $inscricao->selecao->processo->nome }})<br />
            <span class="text-muted">{{ $inscricao->selecao->descricao }}</span><br />
          </div>
        </div>
        @include('inscricoes.partials.instrucoes-da-selecao-badge')
        @include('inscricoes.partials.instrucoes-da-selecao')

        {{ html()->form('post', $data->url . (($modo == 'edit') ? ('/edit/' . $inscricao->id) : '/create/' . $inscricao->selecao->id))
          ->attribute('enctype', 'multipart/form-data')
          ->attribute('id', 'form_principal')
          ->open() }}
          @method($modo == 'edit' ? 'PUT' : 'POST')
          @csrf
          {{ html()->hidden('id') }}
          <input type="hidden" name="selecao_id" value="{{ $inscricao->selecao->id }}">
          <div class="card-body">
            <div class="row">
              <div class="col-md-7">
                @include('inscricoes.partials.principal-card')    {{-- Principal --}}
              </div>
              <div class="col-md-5">
                @if ($modo == 'edit')
                  @include('common.card-arquivos')                {{-- Arquivos --}}
                @endif
              </div>
            </div>
          </div>
        {{ html()->form()->close() }}
      </div>
    </div>
  </div>
@endsection
