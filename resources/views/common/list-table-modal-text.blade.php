<div class="form-group row">
    {!! Html::label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-2')->toHtml() !!}
    <div class="col-sm-10">
        {!! Html::input('text', $col['name'], null)->class('form-control')->toHtml() !!}
    </div>
</div>
