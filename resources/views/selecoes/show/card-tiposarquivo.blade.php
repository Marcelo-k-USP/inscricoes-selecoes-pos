@section('styles')
@parent
  <style>
    #card-tiposarquivo{{ strtolower($tipoarquivo_classe_nome) }} {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<a name="card_tiposarquivo{{ strtolower($tipoarquivo_classe_nome) }}"></a>
<div class="card bg-light mb-3" id="card-tiposarquivo{{ strtolower($tipoarquivo_classe_nome) }}">
  <div class="card-header">
    Tipos de Documentos nas {{ $tipoarquivo_classe_nome_plural_acentuado }}
    @php
      $selecao_tiposarquivo = $selecao->tiposarquivo->where('classe_nome', $tipoarquivo_classe_nome_plural_acentuado)
    @endphp
    <span class="badge badge-pill badge-primary">{{ is_null($selecao_tiposarquivo) ? 0 : $selecao_tiposarquivo->count() }}</span>
    @can('selecoes.update', $selecao)
      @if ($condicao_ativa)
        @php
          $inclusor_url = 'selecoes';    // para o include abaixo
        @endphp
        @include('tiposarquivo.partials.modal-add')
      @endif
    @endcan
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionTiposArquivo{{ $tipoarquivo_classe_nome }}">
      @if (!is_null($tiposarquivo))
        @foreach ($selecao_tiposarquivo as $tipoarquivo)
          <div class="card tipoarquivo-item">
            <div class="card-header" style="font-size:15px">
              @include('tiposarquivo.show.header')
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
