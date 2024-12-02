@section('styles')
@parent
  <style>
    #card-selecao-formulario {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

<div class="card mb-3" id="card-selecao-formulario">
  <div class="card-header">
    <i class="fab fa-wpforms"></i> Formulário
    <span class="small">@include('ajuda.selecoes.formulario')</span>

    <a href="{{ route('selecoes.createtemplate', $selecao->id) }}" class="btn btn-light btn-sm text-primary">
      <i class="fas fa-edit"></i> Editar
    </a>
    @includewhen(Gate::check('perfiladmin'), 'selecoes.partials.btn-template-show-json-modal')
  </div>
  <div class="card-body">
    <div class="ml-2 truncate-text">
      &nbsp; &nbsp; (tipo) Label</strong><br />
      @foreach (json_decode($selecao->template) as $field => $value)
        @if (isset($value->validate) && ($value->validate == 'required'))
          <small class="text-required">(*)</small>
        @else
          &nbsp; &nbsp;
        @endif
        ({{ $value->type }}) {{ $value->label }}<br />
      @endforeach
    </div>
  </div>
</div>
