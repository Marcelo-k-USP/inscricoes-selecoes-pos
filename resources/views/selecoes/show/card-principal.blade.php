@section('styles')
@parent
<style>
    #card-fila-principal {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }
</style>
@endsection

{{ html()->form('post', $data->url . (($modo == 'edit') ? ('/edit/' . $selecao->id) : '/create'))
    ->attribute('id', 'form_principal')
    ->open() }}
    @csrf
    @method($modo == 'edit' ? 'PUT' : 'POST')
    {{ html()->hidden('id') }}
    <div class="card mb-3 w-100" id="card-fila-principal">
        <div class="card-header">
            Informações básicas
        </div>
        <div class="card-body">
            <div class="list_table_div_form">
                @include('common.list-table-form-contents')
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">{{ ($modo == 'edit' ) ? 'Salvar' : 'Prosseguir' }}</button>
            </div>
        </div>
    </div>
{{ html()->form()->close() }}
