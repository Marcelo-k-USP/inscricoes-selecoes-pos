@extends('master')

@section('styles')
@parent
<style>
  .card-funcoes {
    border: 1px solid coral;
    border-top: 3px solid coral;
  }
  .card-container {
    display: flex;
    flex-wrap: wrap;
  }
  .card-container .card {
    flex: 1 1 21%; /* Ajuste a largura conforme necessário */
    margin: 10px;
  }
</style>
@endsection

@section('content')
@parent
  <div class="row">
    <div class="col-md-12">
      {{ html()->form('post', '')->attribute('id', 'form_funcoes')->open() }}
        @csrf
        @method('put')
        {{ html()->hidden('id') }}
        <div class="card-container">
          <div class="card card-funcoes">
            <div class="card-header">
              Secretários(as) dos Programas
            </div>
            <div class="card-body">
              @php
                $programa_anterior = '';
              @endphp
              @foreach ($programas_secretarios as $programa_secretario)
                <div class="card my-2">
                  <div class="card-header py-1" style="font-size: 15px;">
                    @if ($programa_secretario->nome != $programa_anterior)
                      {{ $programa_secretario->nome }}
                      @php
                        $programa_anterior = $programa_secretario->nome;
                      @endphp
                      @include('programas.partials.btn-adicionar-codpes')
                    @endif
                  </div>
                  @if ($programa_secretario->users->count() > 0)
                    <div class="card-body py-1" style="font-size: 14px;">
                      @foreach ($programa_secretario->users as $user)
                        {{ $user->name }}
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
          <div class="card card-funcoes">
            <div class="card-header">
              Coordenadores dos Programas
            </div>
            <div class="card-body">
              @php
                $programa_anterior = '';
              @endphp
              @foreach ($programas_coordenadores as $programa_coordenador)
                <div class="card my-2">
                  <div class="card-header py-1" style="font-size: 15px;">
                    @if ($programa_coordenador->nome != $programa_anterior)
                      {{ $programa_coordenador->nome }}
                      @php
                        $programa_anterior = $programa_coordenador->nome;
                      @endphp
                      @include('programas.partials.btn-adicionar-codpes')
                    @endif
                  </div>
                  @if ($programa_coordenador->users->count() > 0)
                    <div class="card-body py-1" style="font-size: 14px;">
                      @foreach ($programa_coordenador->users as $user)
                        {{ $user->name }}
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
          <div class="card card-funcoes">
            <div class="card-header">
              Serviço de Pós-Graduação
            </div>
            <div class="card-body py-1" style="font-size: 14px;">
              @if (isset($posgraduacao_servico->users))
                @foreach ($posgraduacao_servico->users as $user)
                  {{ $user->name }}
                @endforeach
              @endif
            </div>
          </div>
          <div class="card card-funcoes">
            <div class="card-header">
              Coordenadores da Pós-Graduação
            </div>
            <div class="card-body py-1" style="font-size: 14px;">
              @if (isset($posgraduacao_coordenadores->users))
                @foreach ($posgraduacao_coordenadores->users as $user)
                  {{ $user->name }}
                @endforeach
              @endif
            </div>
          </div>
        </div>
      {{ html()->form()->close() }}
    </div>
  </div>
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">

  </script>
@endsection
