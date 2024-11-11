<div class="form-group row">
    {{ html()->label($col['label'] ?? $col['name'])->for($col['name'])->class('col-form-label col-sm-2') }}
    <div class="col-sm-10">
        <?php
        $table = substr($col['name'],0,-3);
        echo html()->select($col['name'], $col['data']) ->class('form-control') ->placeholder('Selecione um ..') ->toHtml();
        ?>
    </div>
</div>
