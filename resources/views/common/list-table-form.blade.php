 <div class="list_table_div_form">
    {{ html()->form('post', $data->url)->attribute('enctype', 'multipart/form-data')->open() }}
        @method('POST')
        {{ html()->hidden('id') }}

        @foreach ($data->model::getFields() as $col)
            @if (empty($col['type']))
                @include('common.list-table-modal-text')
            @elseif ($col['type'] == 'select')
                @include('common.list-table-modal-select')
            @elseif ($col['type'] == 'files')
                @include('common.list-table-modal-files')
            @endif
        @endforeach
        
        <div class="text-right">
            {{-- vamos adicionar o botão do modal quando for o caso --}}
            @yield('form-dismiss-btn')
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    {{ html()->form()->close() }}
 </div>
