@extends('master')
@section('content')
@parent
  <div class="row">
    <div class="col-md-12">
      {{ html()->form('post', route('selecoes.storetemplatevalue', $selecao->id))->id('valuetemplate-form')->open() }}
        @csrf
        {{ html()->hidden('id') }}
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="card-title form-inline my-0">
              Seleções <i class="fas fa-angle-right mx-2"></i>
              <a href="selecoes/edit/{{ $selecao->id }}">{{ $selecao->nome }}</a>
              @if (!is_null($selecao->descricao))
                &nbsp;- {{ $selecao->descricao }}
              @endif
              &nbsp; | &nbsp;  Formulário <i class="fas fa-angle-right mx-2"></i> Tipo &nbsp; | &nbsp;
              @include('selecoes.partials.btn-template-novocampolista-modal')
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    @if (isset($template['tipo']) && isset($template['tipo']['value']))
                      <div id="template-header" class="form-row">
                        <div class="col-2"><strong>Tipo</strong></div>
                        <div class="col-3"><strong>Label</strong></div>
                        <div class="col"></div>
                      </div>
                      @foreach ($template['tipo']['value'] as $tkey => $tvalue)
                        <div class="form-row mt-2">
                          <div class="col-2">
                            {{ $tvalue['value'] }}
                          </div>
                          <div class="col-3">
                            <input class="form-control" name="value[{{ $tkey }}][label]" value="{{ $tvalue['label'] }}">
                          </div>
                          <div class="col">
                            <button class="btn btn-danger" type="button" onclick="apaga_campo(this)">Apagar</button>
                          </div>
                        </div>
                      @endforeach
                      <br />
                      <button class="btn btn-primary ml-1" type="submit">Salvar</button>
                    @else
                      Não existe tipo para esse formulário.
                      <br />
                      <br />
                    @endif
                    <a class="btn btn-secondary" href="{{ route('selecoes.createtemplate', ['selecao' => $selecao]) }}">Voltar</a>
                  </div>
                </div>
              </div>
            {{ html()->form()->close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>

  <script>
    function apaga_campo(r) {
      if (confirm('Tem certeza que deseja deletar?')) {
        var row = r.parentNode.parentNode;
        row.remove();
        var form = document.getElementById("valuetemplate-form");
        form.requestSubmit();
      }
    }

    function move(r, up) {
      var head = "template-header";
      var tail = "template-new";
      var form = document.getElementById("valuetemplate-form");
      var row = r.parentNode.parentNode;
      if (up) {
        var sibling = row.previousElementSibling;
        if (sibling.id != head) {
          row.parentNode.insertBefore(row, sibling);
          form.requestSubmit();
        }
      } else {
        var sibling = row.nextElementSibling;
        if (sibling.id != tail) {
          row.parentNode.insertBefore(row, sibling.nextSibling);
          form.requestSubmit();
        }
      }
    }

    // Ao carregar a página
    $(document).ready(function() {
      // Pega todos os campos extras que são caixa de seleção
      $('select[name$="][type]"]').each(function () {
        var nameField = $(this).prop('name');
        // muda o campo de input para caixa de texto
        $(mudarCampoInputTextarea(nameField));
      });
    });
  </script>
@endsection
