@extends('layouts.app')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <div class="d-none d-sm-block h4 mt-2">
        Solicitações de Isenção de Taxa
      </div>
      <div class="d-block d-sm-none h4 mt-2">
        {{-- vai mostrar no mobile --}}
        <i class="fas fa-filter"></i>
      </div>
      <div class="h4 mt-1 ml-2">
        <span class="badge badge-pill badge-primary datatable-counter">-</span>
      </div>
      @include('partials.datatable-filter-box', ['otable' => 'oTable'])
    </div>
  </div>

  @if (isset($objetos) && ($objetos->count() > 0))
    <table class="table table-striped tabela-solicitacoesisencaotaxa display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Solicitante</th>
          <th>Seleção</th>
          <th class="text-right">Efetuada em</th>
          <th class="text-right">Atualização</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($objetos as $solicitacaoisencaotaxa)
          <tr>
            <td>
              @include('solicitacoesisencaotaxa.partials.status-small')
              <a href="solicitacoesisencaotaxa/edit/{{ $solicitacaoisencaotaxa->id }}">
                @php
                  $nome = null;
                  if (!is_null($solicitacaoisencaotaxa->extras)) {
                    $extras = json_decode($solicitacaoisencaotaxa->extras);
                    if ($extras && property_exists($extras, 'nome'))
                      $nome = Str::limit($extras->nome, 20);
                  }
                @endphp
                {{ $nome }}</a>
              @include('solicitacoesisencaotaxa.partials.status-muted')
            </td>
            <td>
              {{ $solicitacaoisencaotaxa->selecao->nome }} ({{ $solicitacaoisencaotaxa->selecao->categoria->nome }})
            </td>
            <td class="text-right">
              <span class="d-none">{{ $solicitacaoisencaotaxa->created_at }}</span>
              {{ formatarDataHora($solicitacaoisencaotaxa->created_at) }}
            </td>
            <td class="text-right">
              <span class="d-none">{{ $solicitacaoisencaotaxa->updated_at }}</span>
              {{ formatarDataHora($solicitacaoisencaotaxa->updated_at) }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <br />
    @canany(['perfiladmin', 'perfilgerente'])
      Não há nenhuma solicitação de isenção de taxa cadastrada no sistema.
    @else
      Você não realizou nenhuma solicitação de isenção de taxa para nossos processos seletivos.
    @endcan
  @endif
@stop

@php
  $paginar = (isset($objetos) && ($objetos->count() > 10));
@endphp

@section('javascripts_bottom')
@parent
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
  <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      oTable = $('.tabela-solicitacoesisencaotaxa').DataTable({
        dom:
          't{{ $paginar ? 'p' : '' }}',
          'paging': {{ $paginar ? 'true' : 'false' }},
          'sort': true,
          'order': [
            [3, 'desc']    // ordenado por data de atualização descrescente
          ],
          'fixedHeader': true,
          language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
          }
      });

      // recuperando o storage local
      var datatableFilter = localStorage.getItem('datatableFilter');
      $('#dt-search').val(datatableFilter);

      // vamos aplicar o filtro
      oTable.search($('#dt-search').val()).draw();

      // vamos renderizar o contador de linhas
      $('.datatable-counter').html(oTable.page.info().recordsDisplay);

      // vamos guardar no storage à medida que digita
      $('#dt-search').keyup(function() {
        localStorage.setItem('datatableFilter', $(this).val())
      });
    });
  </script>
@endsection
