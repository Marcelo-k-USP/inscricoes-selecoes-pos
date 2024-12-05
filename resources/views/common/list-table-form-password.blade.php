<div class="form-group row">
  @php
    $col['label'] .= ((in_array('required', $rules[$col['name']] ?? [])) ? ' <small class="text-required">(*)</small>' : '');
  @endphp
  {{ html()->label($col['label'] ?? $col['name'], $col['name'])->class('col-form-label col-sm-3') }}
  <div class="col-sm-3" style="position: relative;">
    {{ html()->input('password', $col['name'])
      ->value(old($col['name'], $modo == 'edit' ? $modelo->{$col['name']} : ''))
      ->class('form-control')
      ->style('width: 100%; padding-right: 30px;')
    }}
    <a href="javascript:void(0);" onclick="toggle_senha()" style="position: absolute; right: 24px; top: 20%; text-decoration: none;">
      <img src="/icons/view.png" id="toggle_icon" style="width: 20px; height: 20px;">
    </a>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
@endsection
