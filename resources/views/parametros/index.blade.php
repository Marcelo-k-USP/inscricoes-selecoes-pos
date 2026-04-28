@extends('master')

@section('content')
<div class="card">
    <div class="card-header">Gerenciar Parâmetros por Programa</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Programa</th>
                    <th>Código Fonte do Recurso</th>
                    <th>Estrutura Hierárquica</th>
                    <th>Link de Acompanhamento</th>
                    <th>E-mail Serviço de Pós-Graduação</th>
                    <th>E-mail Seção de Informática</th>
                    <th>E-mail Gerenciamento do Site</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programas as $prog)
                <tr>
                    <td>{{ $prog->nome }}</td>
                    <td>{{ $prog->parametro->boleto_codigo_fonte_recurso }}</td>
                    <td>{{ $prog->parametro->boleto_estrutura_hierarquica }}</td>
                    <td>{{ $prog->parametro->link_acompanhamento_especiais }}</td>
                    <td>{{ $prog->parametro->email_servicoposgraduacao }}</td>
                    <td>{{ $prog->parametro->email_secaoinformatica }}</td>
                    <td>{{ $prog->parametro->email_gerenciamentosite }}</td>
                    <td class="text-center">
                        <a href="{{ route('parametros.edit', ['id' => $prog->parametro_id, 'programa_id' => $prog->id]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection