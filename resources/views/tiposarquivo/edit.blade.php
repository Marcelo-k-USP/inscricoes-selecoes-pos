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
    $tipoarquivo = $objeto;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title form-inline my-0">
            @if ($modo == 'edit')
              <a href="tiposarquivo">Tipos de Documento</a> <i class="fas fa-angle-right mx-2"></i>
              {{ $objeto->nome }}
              @if (!is_null($objeto->classe_nome))
                &nbsp;({{ $objeto->classe_nome }})
              @endif
            @else
              Novo Tipo de Documento
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @include('tiposarquivo.show.card-principal')              {{-- Principal --}}
            </div>
            <div class="col-md-5">
              @if ($modo == 'edit')
                @if ($objeto->classe_nome == 'Inscrições')
                  @include('tiposarquivo.show.card-niveisprogramas')    {{-- Níveis + Programas --}}
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
