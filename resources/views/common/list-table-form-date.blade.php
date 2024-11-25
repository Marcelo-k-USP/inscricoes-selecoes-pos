<div class="form-group row">
    {{ html()->label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-3') }}
    <div class="col-sm-2">
        {{ html()->input('text', $col['name'])
            ->value(old($col['name'], (($modo == 'edit') && (!is_null($modelo->{$col['name']}))) ? \Carbon\Carbon::parse($modelo->{$col['name']})->format('d/m/Y') : ''))
            ->class('form-control datepicker')
        }}
    </div>
</div>
