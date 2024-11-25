<div class="form-group row">
    {{ html()->label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-3') }}
    <div class="col-sm-2">
        {{ html()->input('text', $col['name'])
            ->value(old($col['name'], $modo == 'edit' ? formatarData($modelo->{$col['name']}) : ''))
            ->class('form-control datepicker')
        }}
    </div>
</div>
