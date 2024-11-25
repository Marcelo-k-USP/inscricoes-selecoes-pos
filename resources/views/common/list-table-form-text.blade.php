<div class="form-group row">
  {{ html()->label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-3') }}
  <div class="col-sm-9">
    {{ html()->input('text', $col['name'])
      ->value(old($col['name'], $modo == 'edit' ? $modelo->{$col['name']} : ''))
      ->class('form-control')
    }}
  </div>
</div>
