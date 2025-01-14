<div class="form-group row">
  @php
    $col['label'] .= ((in_array('required', $rules[str_replace('datahora_', 'data_', $col['name'])] ?? [])) ? ' <small class="text-required">(*)</small>' : '');
  @endphp
  {{ html()->label($col['label'] ?? str_replace('datahora_', 'data_', $col['name']), str_replace('datahora_', 'data_', $col['name']))->class('col-form-label col-sm-3') }}
  <div class="col-sm-4 d-flex align-items-center">
    {{ html()->input('text', str_replace('datahora_', 'data_', $col['name']))
      ->value(old(str_replace('datahora_', 'data_', $col['name']), $modo == 'edit' ? formatarData($objeto->{$col['name']}) : ''))
      ->class('form-control datepicker')
      ->attribute('style', 'width: 106px;')
    }}
    &nbsp; &nbsp;
    {{ html()->input('text', str_replace('datahora_', 'hora_', $col['name']))
      ->value(old(str_replace('datahora_', 'hora_', $col['name']), $modo == 'edit' ? formatarHora($objeto->{$col['name']}) : ''))
      ->class('form-control timefield')
      ->attribute('style', 'display: none;')
    }}
  </div>
</div>
