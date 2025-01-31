@section('styles')
@parent
  <style>
    #card-envio {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

{{ html()->form('post', $data->url . '/edit/' . $objeto->id)
  ->attribute('id', 'form_envio')
  ->attribute('novalidate', '')          // pois faço minha validação manual em $('#form_envio').on('submit'
  ->open() }}
  @csrf
  @method('put')
  {{ html()->hidden('id') }}
  <input type="hidden" id="acao" name="acao" value="envio">
  <div class="card mb-3 w-100" id="card-envio">
    <div class="card-header">
      Envio
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        <div class="form-group row">
          <div class="col-sm-12 d-flex align-items-center" style="gap: 10px;">
            <input class="form-control" style="width: auto; margin: 0;" name="declaro" id="declaro" type="checkbox" required>
            <label style="margin: 0;" for="declaro">Declaro que as informações prestadas são verdadeiras e assumo inteira responsabilidade pelas mesmas. <small class="text-required">(*)</small></label>
          </div>
          <br />
        </div>
      </div>
      @if (session('perfil') == 'usuario')
        <div class="text-right">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      @endif
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      $('#form_envio').each(function () {
        this.oninput = function(e) {
          e.target.setCustomValidity('');
        }
      });
    });

    $('#form_envio').on('submit', function(event) {
      var form_valid = true;

      $('#form_envio [required]').each(function () {
        if (!this.validity.valid) {
          form_valid = false;
          switch (this.type) {
            case 'checkbox':
              if (!this.checked)
                return mostrar_validacao(this, 'Favor marcar esta opção');
          }
        }
      });

      if (!form_valid)
        event.preventDefault();
    });

    function mostrar_validacao(obj, msg)
    {
      obj.setCustomValidity(msg);
      obj.reportValidity();
      return false;
    }
  </script>
@endsection
