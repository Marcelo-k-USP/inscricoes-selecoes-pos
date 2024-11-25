<div class="form-group row">
  {{ html()->label($col['label'] ?? $col['name'])->for($col['name'])->class('col-form-label col-sm-3') }}
  <div class="col-sm-9">
    {{ html()->select($col['name'], [])
      ->class('form-control')
      ->placeholder('Selecione um ..')
    }}
  </div>
</div>
