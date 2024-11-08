<div class="form-group row">
    {!! Html::label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-2')->toHtml() !!}
    <div class="col-sm-10">
        <?php
        $table = substr($col['name'],0,-3);
        echo Html::select($col['name'], $col['data'])->class('form-control')->placeholder('Selecione um ..')->toHtml();
        ?>
    </div>
</div>
