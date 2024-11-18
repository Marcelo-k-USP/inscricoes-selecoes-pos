@foreach ($data->model::getFields() as $col)
    @if (empty($col['type']))
        @include('common.list-table-form-text')
    @elseif ($col['type'] == 'select')
        @include('common.list-table-form-select')
    @endif
@endforeach
