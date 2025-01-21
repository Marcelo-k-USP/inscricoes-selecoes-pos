@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Nova Inscrição</span>
      @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    </div>
  </div>

  @php
    $existem_selecoes = false;
    foreach ($categorias as $categoria)
      if ($categoria->selecoes->count() > 0) {
        $existem_selecoes = true;
        break;
      }
  @endphp

  @if ($existem_selecoes)
    <br />
    Para qual processo seletivo e linha de pesquisa você deseja se inscrever?<br />
    <table class="table table-sm table-hover nova-inscricao display responsive" style="width: 100%;">
      <thead>
        <tr>
          <th style="border: none;"><span class="d-none">Seleções</span></td>
        </tr>
      </thead>
      <tbody>
        @foreach ($categorias as $categoria)
          @if ($categoria->selecoes->count())
            <tr>
              <td>
                {{ $categoria->nome }}
                @foreach ($categoria->selecoes as $selecao)
                  <div class="ml-3">
                    {{ $selecao->nome }} @if (!is_null($selecao->descricao)) - {{ $selecao->descricao }} @endif
                    @foreach ($selecao->linhaspesquisa as $linhapesquisa)
                      <div class="ml-3">
                        <a href="inscricoes/create/{{ $selecao['id'] }}/{{ $linhapesquisa['id'] }}">
                          {{ $linhapesquisa->nome }}
                        </a>
                      </div>
                    @endforeach
                  </div>
                @endforeach
                <br>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  @else
    <br />
    Não há processos seletivos ocorrendo no momento.
  @endif
@endsection

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {
      oTable = $('.nova-inscricao').DataTable({
        dom:
          't',
          'paging': false,
          'sort': false
      });
    });
  </script>
@endsection
