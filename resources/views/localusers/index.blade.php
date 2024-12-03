@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Usuários Locais</span>
      @include('partials.datatable-filter-box', ['otable'=>'oTable'])
      <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Novo
      </button>
    </div>
  </div>

  @include('localusers.partials.modal')

  @if (isset($localusers) && ($localusers->count() > 0))
    <table class="table table-striped table-hover datatable-nopagination display responsive" style="width:100%">
      <thead>
        <tr>
          <th width="20%">Nome de Usuário</th>
          <th width="40%">Nome</th>
          <th width="20%">E-mail</th>
          <th width="20%">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($localusers as $localuser)
          <tr>
            <td>{{ $localuser->codpes }}</td>
            <td>{{ $localuser->name }}</td>
            <td>{{ $localuser->email }}</td>
            <td>
              <div id="actions">
                @include('localusers.partials.btn-edit')
                @include('localusers.partials.btn-delete')
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

  <script>
    $(document).ready(function() {

      oTable = $('.datatable-nopagination').DataTable({
        dom:
          't{{ $paginar ? 'p' : '' }}',
          'paging': {{ $paginar ? 'true' : 'false' }},
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
