@extends('master')
@section('content')
@parent
  @include('common.modal-processando')
  <div class="row">
    <div class="col-md-12">
      {{ html()->form('post', route('selecoes.storetemplate', $selecao->id))->id('template-form')->open() }}
        @csrf
        {{ html()->hidden('id') }}
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="card-title form-inline my-0">
              Seleções <i class="fas fa-angle-right mx-2"></i>
              <a href="selecoes/edit/{{ $selecao->id }}">{{ $selecao->nome }}</a>
              @if (!is_null($selecao->categoria))
                &nbsp;({{ $selecao->categoria->nome }})
              @endif
              &nbsp; | &nbsp;  Formulário &nbsp; | &nbsp; &nbsp;
              @include('selecoes.partials.btn-template-novocampo-modal')
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    @if ($template)
                      <div id="template-header" class="form-row">
                        <div class="col"><strong>Campo</strong></div>
                        @foreach ($selecao->getTemplateFields() as $field)
                          <div class="col"><strong>{{ ucfirst($field) }}</strong></div>
                        @endforeach
                        <div class="col"></div>
                      </div>
                      @php
                        $i = 0;
                      @endphp
                      @foreach ($template as $tkey => $tvalue)
                        <div class="form-row mt-2" id="linha_{{ $i }}">
                          <div class="col truncate-text">{{ $tkey }}</div>
                          @foreach ($selecao->getTemplateFields() as $field)
                            <div class="col">
                              @isset($tvalue[$field])
                                @switch($field)
                                  @case('type')
                                    <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]" onchange="javascript: mudarCampoInputTextarea(this.name);">
                                      <option value='text' {{ $tvalue[$field] == 'text' ? 'selected' : '' }}>Texto</option>
                                      <option value='select' {{ $tvalue[$field] == 'select' ? 'selected' : '' }}>Caixa de Seleção</option>
                                      <option value='date' {{ $tvalue[$field] == 'date' ? 'selected' : '' }}>Data</option>
                                      <option value='number' {{ $tvalue[$field] == 'number' ? 'selected' : '' }}>Número</option>
                                      <option value='radio' {{ $tvalue[$field] == 'radio' ? 'selected' : '' }}>Botão de Opção</option>
                                      <option value='checkbox' {{ $tvalue[$field] == 'checkbox' ? 'selected' : '' }}>Caixa de Verificação</option>
                                    </select>
                                    @break
                                  @case('validate')
                                    <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                      <option value='' {{ $tvalue[$field] == '' ? 'selected' : '' }}>Sem validação</option>
                                      <option value='required' {{ $tvalue[$field] == 'required' ? 'selected' : '' }}>Obrigatório</option>
                                      {{-- <option value='required|integer' {{ $tvalue[$field] == 'required|integer' ? 'selected' : '' }}>Obrigatório - Somente números</option> --}}
                                    </select>
                                    @break
                                  @case('can')
                                    <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                      <option value='' {{ $tvalue[$field] == '' ? 'selected' : '' }}>Exibido para todos</option>
                                      <option value='atendente' {{ $tvalue[$field] == 'atendente' ? 'selected' : '' }}>Somente Atendentes</option>
                                    </select>
                                    @break
                                  @case('value')
                                    <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="{{ is_array($tvalue[$field]) ? json_encode($tvalue[$field], JSON_UNESCAPED_UNICODE) : $tvalue[$field] ?? '' }}" @if (!Gate::check('perfiladmin')) type="hidden" @endif>
                                    <a href="{{ route('selecoes.createtemplatevalue', ['selecao' => $selecao->id, 'campo' => $tkey]) }}" class="btn btn-primary btn-sm">
                                      <i class="fas fa-edit"></i> Editar Lista
                                    </a>
                                    @break
                                  @default
                                    <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="{{ is_array($tvalue[$field]) ? json_encode($tvalue[$field], JSON_UNESCAPED_UNICODE) : $tvalue[$field] ?? '' }}">
                                @endswitch
                              @endisset
                              @if(empty($tvalue[$field]) && !isset($tvalue[$field]))
                                @switch($field)
                                  @case('validate')
                                    <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                      <option value=''>Sem validação</option>
                                      <option value='required'>Obrigatório</option>
                                      <option value='required|integer'>Obrigatório - Somente números</option>
                                    </select>
                                    @break
                                  @case('can')
                                    <select class="form-control" name="template[{{ $tkey }}][{{ $field }}]">
                                      <option value=''>Exibido para todos</option>
                                      <option value='atendente'>Somente Atendentes</option>
                                    </select>
                                    @break
                                  @default
                                    <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="">
                                @endswitch
                              @endif
                            </div>
                          @endforeach
                          <div class="col">
                            <button class="btn btn-danger" type="button" onclick="apaga_campo(this)">Apagar</button>
                            <input type="hidden" name="template[{{ $tkey }}][order]" id="index[{{ $i }}]" value="{{ $i }}">
                            <button class="btn btn-success" type="button" onclick="move(this, 1)">&#8679;</button>
                            <button class="btn btn-success" type="button" onclick="move(this, 0)">&#8681;</button>
                          </div>
                        </div>
                        @php
                          $i++;
                        @endphp
                      @endforeach
                    @else
                      Não existe formulário para essa seleção.
                      <br />
                    @endif
                    <br />
                    @if ($template)
                      <button class="btn btn-primary ml-1" type="submit">Salvar</button>
                    @endif
                    <a class="btn btn-secondary" href="{{ route('selecoes.edit', ['selecao' => $selecao->id]) }}">Voltar</a>
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
        $('#modal_processando').modal('show');
        var form = document.getElementById('template-form');
        form.requestSubmit();
      }
    }

    function move(r, up) {
      var head = 'template-header';
      var tail = 'template-new';
      var form = document.getElementById('template-form');
      var row = r.parentNode.parentNode;
      var i = parseInt(row.id.split('_')[1]);
      if (up) {
        var sibling = row.previousElementSibling;
        if (sibling.id != head) {
          row.parentNode.insertBefore(row, sibling);
          $('#modal_processando').modal('show');
          $('input[id="index[' + i + ']"]').val(i - 1);
          $('input[id="index[' + (i - 1) + ']"]').val(i);
          form.requestSubmit();
        }
      } else {
        var sibling = row.nextElementSibling;
        if (sibling.id) {
          row.parentNode.insertBefore(row, sibling.nextSibling);
          $('#modal_processando').modal('show');
          $('input[id="index[' + i + ']"]').val(i + 1);
          $('input[id="index[' + (i + 1) + ']"]').val(i);
          form.requestSubmit();
        }
      }
    }

    $(document).ready(function() {
      $('select[name$="][type]"]').each(function () {
        $(mudarCampoInputTextarea($(this).prop('name')));
      });
    });
  </script>
@endsection
