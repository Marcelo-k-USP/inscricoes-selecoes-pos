@extends('master')

@section('content')
<div class="card">
    <div class="card-header">Parâmetros por Programa</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Programa</th>
                    <th>Fonte do Recurso</th>
                    <th>Centro Gerencial</th>
                    <th>Link de Acompanhamento</th>
                    <th>E-mail da Pós</th>
                    <th>E-mail da Informática</th>
                    <th>E-mail Gerenciamento do Site</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programas as $prog)
                <tr>
                    <td>{{ $prog->nome }}</td>
                    <td>{{ $prog->parametro->boleto_codigo_fonte_recurso ?? null }}</td>
                    <td>{{ $prog->parametro->boleto_estrutura_hierarquica ?? null }}</td>
                    <td><a href="{{ $prog->parametro->link_acompanhamento_especiais ?? null }}">{{ $prog->parametro->link_acompanhamento_especiais ?? null }}</a></td>
                    <td>{{ $prog->parametro->email_servicoposgraduacao ?? null }}</td>
                    <td>{{ $prog->parametro->email_secaoinformatica ?? null }}</td>
                    <td>{{ $prog->parametro->email_gerenciamentosite ?? null }}</td>
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