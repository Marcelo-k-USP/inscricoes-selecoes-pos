@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Seleções</span>
      @include('partials.datatable-filter-box', ['otable'=>'oTable'])
      @can('selecoes.viewAny')
        <a href="{{ route('selecoes.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-plus"></i> Nova
        </a>
      @endcan
    </div>
  </div>

  <table class="table table-striped table-hover datatable-nopagination display responsive" style="width:100%">
    <thead>
      <tr>
        <th>Nro</th>
        <th></th>
        <th>Nome</th>
        <th>Programa</th>
        <th>Categoria</th>
        <th width="10%">Criada em</th>
        <th width="10%">Atualização</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($objetos as $selecao)
        <tr>
          <td>
            <a class="mr-2" href="selecoes/edit/{{ $selecao->id }}">{{ $selecao->id }}</a>
          </td>
          <td>
            @include('selecoes.partials.status-small')
          </td>
          <td>
            {{ $selecao->nome }}
            @include('selecoes.partials.status-muted')
          </td>
          <td>{{ $selecao->programa?->nome ?? 'N/A' }}</td>
          <td>{{ $selecao->categoria->nome }}</td>
          <td class="text-right">
            <span class="d-none">{{ $selecao->created_at }}</span>
            {{ formatarDataHora($selecao->created_at) }}
          </td>
          <td class="text-right">
            <span class="d-none">{{ $selecao->updated_at }}</span>
            {{ formatarDataHora($selecao->updated_at) }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@php
  $paginar = (isset($objetos) && ($objetos->count() > 10));
@endphp

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {
      oTable = $('.datatable-nopagination').DataTable({
        dom:
          't{{ $paginar ? 'p' : '' }}',
          'paging': {{ $paginar ? 'true' : 'false' }},
          'sort': true,
          'order': [
            [6, 'desc']    // ordenado por data de atualização descrescente
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
    });
  </script>
@endsection
