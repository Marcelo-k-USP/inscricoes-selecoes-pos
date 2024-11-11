@extends('master')

@section('content')
@parent

<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Nova inscrição</span>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    </div>
</div>

<table class="table table-sm table-hover nova-inscricao display responsive" style="width:100%"">
    <thead>
        <tr>
            <td><span class="d-none">Seleções</span></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($processos as $processo)
            @if($processo->selecoes->count())
            <tr>
                <td>
                    {{ $processo->nome }}
                    @foreach ($processo->selecoes as $selecao)
                    <div class="ml-3">
                        <a href="inscricoes/create/{{ $selecao['id'] }}">{{ $selecao->nome }}</a> - {{ $selecao->descricao }}
                    </div>
                    @endforeach
                    <br>
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>


@endsection

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.nova-inscricao').DataTable({
            dom: 't'
            , "paging": false
            , "sort": false
        });

    })

</script>
@endsection
