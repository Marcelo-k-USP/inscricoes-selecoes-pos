@extends('master')

@section('content')
@parent
  @if ($localusers != null)
    @include('localusers.partials.main')
    @include('localusers.partials.modal')
  @endif

  @if (isset($users) && ($users->count() > 0))
    <table class="table table-striped table-hover datatable-nopagination display responsive" style="width:100%">
      <thead>
        <tr>
          <th width="20%">Nome de usuário</th>
          <th width="40%">Nome</th>
          <th width="20%">E-mail</th>
          <th width="20%">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr>
            <td>{{ $user->codpes }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              <div id="actions">
                <a href="/localusers/{{$user->id}}/edit"><i class="fas fa-edit"></i></a>
                <form method="POST" action="/localusers/{{ $user->id }}">
                  @csrf
                  @method('delete')
                  <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                    <a><i class="fas fa-trash" color="#007bff" id="i-trash"></i></a>
                  </button>
                </form>
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
  $paginar = (isset($users) && ($users->count() > 10));
@endphp

@section('javascripts_bottom')
@parent
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
  <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>

  <script>
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
