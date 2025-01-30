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
    $linhapesquisa = $objeto;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title form-inline my-0">
            @if ($modo == 'edit')
              <a href="linhaspesquisa">Linhas de Pesquisa/Temas</a> <i class="fas fa-angle-right mx-2"></i>
              {{ $objeto->nome }}
              @if (!is_null($objeto->programa))
                &nbsp;({{ $objeto->programa->nome }})
              @endif
            @else
              Nova Linha de Pesquisa/Tema
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @include('linhaspesquisa.show.card-principal')              {{-- Principal --}}
            </div>
            <div class="col-md-5">
              @if ($modo == 'edit')
                @include('linhaspesquisa.show.card-niveis')               {{-- Níveis --}}
                @include('linhaspesquisa.show.card-orientadores')         {{-- Orientadores --}}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
