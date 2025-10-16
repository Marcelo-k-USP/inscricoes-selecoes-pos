@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <div class="d-none d-sm-block h4 mt-2">
        Usuários Locais
      </div>
      <div class="d-block d-sm-none h4 mt-2">
        {{-- vai mostrar no mobile --}}
        <i class="fas fa-filter"></i>
      </div>
      <div class="h4 mt-1 ml-2">
        <span class="badge badge-pill badge-primary datatable-counter">-</span>
      </div>
      @include('partials.datatable-filter-box', ['otable' => 'oTable'])
      @can('localusers.create')
        <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
          <i class="fas fa-plus"></i> Novo
        </button>
      @endcan
    </div>
  </div>

  @include('localusers.partials.modal')

  @if (isset($localusers) && ($localusers->count() > 0))
    <table class="table table-striped table-hover datatable-nopagination display responsive" style="width:100%">
      <thead>
        <tr>
          <th width="25%">Nome</th>
          <th width="25%">E-mail</th>
          <th width="20%">Confirmado?</th>
          <th width="20%">Criado em</th>
          <th width="10%">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($localusers as $localuser)
          <tr>
            <td>{{ $localuser->name }}</td>
            <td>{{ $localuser->email }}</td>
            <td>{{ $localuser->email_confirmado ? 'Sim' : 'Não' }}</td>
            <td data-order="{{ $localuser->created_at }}">{{ formatarDataHora($localuser->created_at) }}</td>
            <td>
              <div id="actions">
                @can('localusers.update')
                  @include('localusers.partials.btn-edit')
                @endcan
                @can('localusers.delete')
                  @include('localusers.partials.btn-delete')
                @endcan
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <br />
    Não há usuários locais.
  @endif
@endsection

@php
  $paginar = (isset($localusers) && ($localusers->count() > 10));
@endphp

@section('javascripts_bottom')
@parent
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
  <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      oTable = $('.datatable-nopagination').DataTable({
        dom:
          't{{ $paginar ? 'p' : '' }}',
          'paging': {{ $paginar ? 'true' : 'false' }},
          'order': [
            [2, 'desc']    // ordenado por data de criação descrescente
          ],
          columnDefs: [
            { targets: -1, orderable: false }    // desativa ordenação da última coluna
          ]
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
