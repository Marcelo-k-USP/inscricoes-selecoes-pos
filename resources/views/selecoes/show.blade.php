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

@include('common.list-table-modal')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title form-inline my-0">
                    <a href="selecoes">Seleções</a> <i class="fas fa-angle-right mx-2"></i> {{ $selecao->nome }} | &nbsp;
                    @include('selecoes.partials.enable-disable-btn')
                </div>
            </div>

            <div class="card-body {{ $selecao->estado == 'Desativada' ? 'disable-links': '' }}">
                <div class="row">
                    <div class="col-md-7">
                        @include('selecoes.partials.principal-card') {{-- Principal --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
