<div class="form-group row">
  @php
    $col['label'] .= (array_filter($rules[$col['name']] ?? [], fn($rule) => str_starts_with($rule, 'required')) ? ' <small class="text-required">(*)</small>' : '');
  @endphp
  {{ html()->label($col['label'] ?? $col['name'])->for($col['name'])->class('col-form-label col-sm-3') }}
  <div class="col-sm-9">
    {{ html()->select($col['name'], $col['data'])
      ->value(old($col['name'], $modo == 'edit' ? $objeto->{$col['name']} : ''))
      ->class('form-control')
      ->placeholder('Selecione...')
    }}
  </div>
</div>
