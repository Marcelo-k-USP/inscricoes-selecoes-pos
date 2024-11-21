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
                            <a href="selecoes">Seleções</a>
                            <i class="fas fa-angle-right mx-2"></i> {{ $selecao->nome }} | &nbsp;
                            @include('selecoes.partials.enable-disable-btn')
                        @else
                            Nova Seleção
                        @endif
                    </div>
                </div>
                <div class="card-body {{ ($modo == 'edit') && ($selecao->estado == 'Encerrada') ? 'disable-links': '' }}">
                    <div class="row">
                        <div class="col-md-7">
                            @include('selecoes.show.card-principal')        {{-- Principal --}}
                            @include('selecoes.show.card-linhaspesquisa')   {{-- Linhas de Pesquisa --}}
                        </div>
                        <div class="col-md-5">
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
