@extends('layouts.app')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <div class="d-none d-sm-block h4 mt-2">
        Inscrições
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
    <table class="table table-striped tabela-inscricoes display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Inscrito</th>
          <th>Seleção</th>
          <th class="text-right">Efetuada em</th>
          <th class="text-right">Atualização</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($objetos as $inscricao)
          <tr>
            <td>
              @include('inscricoes.partials.status-small')
              <a href="inscricoes/edit/{{ $inscricao->id }}">
                @php
                  $nome = null;
                  if (!is_null($inscricao->extras)) {
                    $extras = json_decode($inscricao->extras);
                    if ($extras && property_exists($extras, 'nome'))
                      $nome = Str::limit($extras->nome, 20);
                  }
                @endphp
                {{ $nome }}</a>
              @include('inscricoes.partials.status-muted')
            </td>
            <td>
              {{ $inscricao->selecao->nome }} ({{ $inscricao->selecao->categoria->nome }})
            </td>
            <td class="text-right">
              <span class="d-none">{{ $inscricao->created_at }}</span>
              {{ formatarDataHora($inscricao->created_at) }}
            </td>
            <td class="text-right">
              <span class="d-none">{{ $inscricao->updated_at }}</span>
              {{ formatarDataHora($inscricao->updated_at) }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <br />
    @canany(['perfiladmin', 'perfilgerente'])
      Não há nenhuma inscrição cadastrada no sistema.
    @else
      Você não realizou nenhuma inscrição para nossos processos seletivos.
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

      oTable = $('.tabela-inscricoes').DataTable({
        dom:
          't{{ $paginar ? 'p' : '' }}',
          'paging': {{ $paginar ? 'true' : 'false' }},
          'sort': true,
          'order': [
            [3, 'desc']    // ordenado por data de atualização descrescente
          ],
          'fixedHeader': true,
          columnDefs: [{
            targets: 1,
            orderable: false
          }],
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
