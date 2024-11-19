<div class="form-group row">
    {{ html()->label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-2') }}
    <div class="col-sm-10">
        {{ html()->input('text', $col['name'])
            ->value(old($col['name'], $modo == 'edit' ? $modelo->{$col['name']} : ''))
            ->class('form-control')
        }}
    </div>
</div>
