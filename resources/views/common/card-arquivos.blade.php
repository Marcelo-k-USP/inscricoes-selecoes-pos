@section('styles')
@parent
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="css/arquivos.css">
  <style>
    #card-arquivos {
      border: 1px solid DarkGoldenRod;
      border-top: 3px solid DarkGoldenRod;
    }
  </style>
@endsection

{{ html()->form('post', $data->url . '/edit/' . $objeto->id)
  ->attribute('enctype', 'multipart/form-data')
  ->attribute('id', 'form_arquivos')
  ->open() }}
  @csrf
  @method('put')
  {{ html()->hidden('id') }}
  <a name="card_arquivos"></a>
  <div class="card bg-light mb-3 w-100" id="card-arquivos">
    <div class="card-header form-inline">
      Documentos
      <span data-toggle="tooltip" data-html="true" title="Tamanho máximo de cada arquivo: {{ $max_upload_size }}KB ">
        <i class="fas fa-question-circle text-secondary ml-2"></i>
      </span>
    </div>
    <div class="card-body">
      <input type="hidden" name="classe_nome" value="{{ $classe_nome }}">
      <input type="hidden" name="objeto_id" value="{{ $objeto->id }}">
      <input type="hidden" name="tipo_arquivo" id="tipo_arquivo">
      <input type="hidden" name="nome_arquivo" id="nome_arquivo">
      @php
        $i = 0;
      @endphp
      @foreach ($objeto->tiposArquivo() as $tipo_arquivo)
        <div class="arquivos-lista">
          {{ $tipo_arquivo['nome'] }} {!! ((isset($tipo_arquivo['validate']) && $tipo_arquivo['validate']) ? '<small class="text-required">(*)</small>' : '') !!}
          @php
            $editable = !(isset($tipo_arquivo['editable']) && ($tipo_arquivo['editable'] == 'none'));
          @endphp
          @if (Gate::allows($classe_nome_plural . '.update', $objeto) && $editable)
            <label for="input_arquivo_{{ $i }}">
              <span class="btn btn-sm btn-light text-primary ml-2"> <i class="fas fa-plus"></i> Adicionar</span>
            </label>
          @endcan
          <input type="hidden" id="tipo_arquivo_{{ $i }}" value="{{ $tipo_arquivo['nome'] }}">
          <input type="file" name="arquivo[]" id="input_arquivo_{{ $i }}" accept="image/jpeg,image/png,application/pdf" class="d-none" multiple capture="environment">

          @if ($objeto->arquivos->where('pivot.tipo', $tipo_arquivo['nome'])->count() > 0)
            <ul class="list-unstyled">
              @foreach ($objeto->arquivos->where('pivot.tipo', $tipo_arquivo['nome']) as $arquivo)
                @if (preg_match('/pdf/i', $arquivo->mimeType))
                  <li class="modo-visualizacao">
                    @if (Gate::allows($classe_nome_plural . '.update', $objeto) && $editable)
                      <div class="arquivo-acoes d-inline-block">
                        <a onclick="excluir_arquivo({{ $arquivo->id }}, '{{ $arquivo->nome_original }}'); return false;" class="btn btn-outline-danger btn-sm btn-deletar btn-arquivo-acao">
                          <i class="far fa-trash-alt"></i>
                        </a>
                        <a onclick="toggle_modo_edicao(this, {{ $arquivo->id }});" class="btn btn-outline-warning btn-sm btn-editar btn-arquivo-acao">
                          <i class="far fa-edit"></i>
                        </a>
                      </div>
                    @endif
                    <a href="arquivos/{{ $arquivo->id }}" title="{{ $arquivo->nome_original }}" class="nome-arquivo-display"><i class="fas fa-file-pdf"></i>
                      <span>{{ $arquivo->nome_original }}</span>
                    </a>
                    <div class="editar-nome-arquivo-form">
                      <div class="input-wrapper">
                        <input type="hidden" id="nome_arquivo_original_{{ $arquivo->id }}" value="{{ pathinfo($arquivo->nome_original, PATHINFO_FILENAME) }}">
                        <input type="text" id="nome_arquivo_{{ $arquivo->id }}" class="input-nome-arquivo" value="{{ pathinfo($arquivo->nome_original, PATHINFO_FILENAME) }}">
                      </div>
                      <div class="btns-wrapper">
                        <a onclick="alterar_arquivo({{ $arquivo->id }}); return false;" class="btn btn-outline-success btn-sm ml-2 btn-arquivo-acao">
                          <i class="fas fa-check"></i>
                        </a>
                        <a onclick="toggle_modo_edicao(this, {{ $arquivo->id }});" class="btn btn-outline-danger btn-sm btn-arquivo-acao limpar-edicao-nome">
                          <i class="fas fa-times"></i>
                        </a>
                      </div>
                    </div>
                  </li>
                @endif
              @endforeach
            </ul>
          @endif
        </div>
        @php
          $i++;
        @endphp
      @endforeach
    </div>
  </div>
{{ html()->form()->close() }}

@include('common.modal-processando')

@section('javascripts_bottom')
@parent
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script type="text/javascript">
    var max_upload_size = {{ $max_upload_size }};
    var count_tipos_arquivo = {{ count($objeto->tiposArquivo()) }};
  </script>
  <script src="js/arquivos.js"></script>
@endsection
