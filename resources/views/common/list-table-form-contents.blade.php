@foreach ($data->model::getFields() as $col)
  @if (empty($col['type']))
    @include('common.list-table-form-text')
  @elseif ($col['type'] == 'password')
    @include('common.list-table-form-password')
  @elseif ($col['type'] == 'date')
    @include('common.list-table-form-date')
  @elseif ($col['type'] == 'datetime')
    @include('common.list-table-form-datetime')
  @elseif ($col['type'] == 'number')
    @include('common.list-table-form-number')
  @elseif ($col['type'] == 'integer')
    @include('common.list-table-form-integer')
  @elseif ($col['type'] == 'select')
    @include('common.list-table-form-select')
  @elseif ($col['type'] == 'checkbox')
    @include('common.list-table-form-checkbox')
  @endif
@endforeach
