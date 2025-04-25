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
        <th>Categoria</th>
        <th>Programa</th>
        <th>Nome</th>
        <th>Solicitações de Isenção de Taxa<br />Início</th>
        <th>Fim</th>
        <th>Inscrições<br />Início</th>
        <th>Fim</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($objetos as $selecao)
        <tr>
          <td>{{ $selecao->categoria->nome }}</td>
          <td>{{ $selecao->programa?->nome ?? 'N/A' }}</td>
          <td>
            @include('selecoes.partials.status-small')
            <a class="mr-2" href="selecoes/edit/{{ $selecao->id }}">{{ $selecao->nome }}</a>
            @include('selecoes.partials.status-muted')
          </td>
          <td>{{ formatarDataHora($selecao->solicitacoesisencaotaxa_datahora_inicio) }}</td>
          <td>{{ formatarDataHora($selecao->solicitacoesisencaotaxa_datahora_fim) }}</td>
          <td>{{ formatarDataHora($selecao->inscricoes_datahora_inicio) }}</td>
          <td>{{ formatarDataHora($selecao->inscricoes_datahora_fim) }}</td>
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
          'paging': {{ $paginar ? 'true' : 'false' }}
      });
    });
  </script>
@endsection
