@section('styles')
@parent
  <style>
    #card-localuser-principal {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

{{ html()->form('post', $data->url)
  ->attribute('id', 'form_principal_localuser')
  ->attribute('novalidate', '')          // pois faço minha validação manual em $('#form_principal_localuser').on('submit'
  ->open() }}
  @csrf
  {{ html()->hidden('id') }}
  <div class="card mb-3 w-100" id="card-localuser-principal">
    <div class="card-header">
      Informações básicas
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        @include('common.list-table-form-contents')
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <div class="g-recaptcha" data-sitekey="{{ config('inscricoes-selecoes-pos.recaptcha_site_key') }}"></div> &nbsp; &nbsp;
        </div>
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      $('#form_principal_localuser').find(':input:visible:first').focus();

      $('#form_principal_localuser').each(function () {
        this.oninput = function(e) {
          e.target.setCustomValidity('');
        }
      });

      $('input[id="telefone"]').each(function() {
        $(this).mask('(00) 00000-0000');
      });
    });

    $('#form_principal_localuser').on('submit', function(event) {
      var form_valid = true;

      $('#form_principal_localuser [required]').each(function () {
        if (!this.validity.valid) {
          form_valid = false;
          switch (this.type) {
            case 'email':
              if (this.value !== '')
                return mostrar_validacao(this, 'E-mail inválido');
              else
                return mostrar_validacao(this, 'Favor preencher este campo');
            default:
              if (this.value === '')
                return mostrar_validacao(this, 'Favor preencher este campo');
          }
        }
      });

      if (!form_valid)
        event.preventDefault();
      else if (grecaptcha.getResponse().length === 0) {
        event.preventDefault();
        window.alert('Favor preencher o captcha');
      }
    });

    $('#password').on('input', function () {
      validar_forca_senha($(this).val());
    });

    function mostrar_validacao(obj, msg)
    {
      obj.setCustomValidity(msg);
      obj.reportValidity();
      return false;
    }
  </script>
@endsection
