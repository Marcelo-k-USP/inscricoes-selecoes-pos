@section('styles')
@parent
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="css/arquivos.css">
  <style>
    #card-informativos {
      border: 1px solid DarkGoldenRod;
      border-top: 3px solid DarkGoldenRod;
    }
  </style>
@endsection

<a name="card_informativos"></a>
<div class="card bg-light mb-3 w-100" id="card-informativos">
  <div class="card-header form-inline">
    Informativos
  </div>
  <div class="card-body">
    @foreach ($tiposarquivo_selecao as $tipoarquivo)
      <div class="arquivos-lista">
        {{ $tipoarquivo['nome'] }}

        @if ($selecao->arquivos->where('pivot.tipo', $tipoarquivo['nome'])->count() > 0)
          <ul class="list-unstyled">
            @foreach ($selecao->arquivos->where('pivot.tipo', $tipoarquivo['nome']) as $arquivo)
              @if (preg_match('/^(application\/pdf|image\/png|image\/jpeg)$/i', $arquivo->mimeType))
                <li class="modo-visualizacao">
                  <a href="arquivos/{{ $arquivo->id }}" title="{{ $arquivo->nome_original }}" class="nome-arquivo-display"><i class="fas fa-file-pdf"></i>
                    <span>{{ $arquivo->nome_original }}</span>
                  </a>
                </li>
              @endif
            @endforeach
          </ul>
        @endif
      </div>
    @endforeach
  </div>
</div>

@include('common.modal-processando')

@section('javascripts_bottom')
@parent
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
@endsection
