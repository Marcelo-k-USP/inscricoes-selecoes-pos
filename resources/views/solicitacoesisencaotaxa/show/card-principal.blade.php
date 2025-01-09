@section('styles')
@parent
  <style>
    #card-solicitacaoisencaotaxa-principal {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

{{ html()->form('post', $data->url . (($modo == 'edit') ? ('/edit/' . $solicitacaoisencaotaxa->id) : '/create'))
  ->attribute('id', 'form_principal_solicitacaoisencaotaxa')
  ->attribute('novalidate', '')          // pois faço minha validação manual em $('#form_principal_solicitacaoisencaotaxa').on('submit'
  ->open() }}
  @csrf
  @method($modo == 'edit' ? 'put' : 'post')
  {{ html()->hidden('id') }}
  <input type="hidden" id="selecao_id" name="selecao_id" value="{{ $solicitacaoisencaotaxa->selecao->id }}">
  <div class="card mb-3 w-100" id="card-solicitacaoisencaotaxa-principal">
    <div class="card-header">
      Informações básicas
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        @if (isset($form))
          {{-- campos nome, tipo_de_documento, numero_do_documento, cpf e e_mail --}}
          @foreach ($form as $input)
            @if (is_array($input))
              @php
                $input_id = null;
                if (preg_match('/id="extras\[(.+?)\]"/', $input[0], $matches))
                  $input_id = $matches[1];
              @endphp
              @if ($input_id && in_array($input_id, ['nome', 'tipo_de_documento', 'numero_do_documento', 'cpf', 'e_mail']))    {{-- somente estes campos do formulário da seleção são preenchidos neste momento de solicitação de isenção de taxa --}}
                <div class="form-group row">
                  @foreach ($input as $element)
                    {!! $element !!}<br />
                  @endforeach
                </div>
              @endif
            @endif
          @endforeach
          {{-- campo Motivo da Solicitação --}}
          <div class="form-group row">
            <div class="col-sm-3">
              <label class="col-form-label va-middle" for="extras[motivo_isencao_taxa]">Motivo da <span style="white-space: nowrap;">Solicitação <small class="text-required">(*)</small></span></label>
            </div>
            <div class="col-sm-9">
              <select class="form-control w-100" name="extras[motivo_isencao_taxa]" id="extras[motivo_isencao_taxa]" required>
                <option value="" disabled selected>Selecione...</option>
                @foreach ($motivosisencaotaxa as $motivoisencaotaxa)
                  <option value="{{ $motivoisencaotaxa->id }}">{{ $motivoisencaotaxa->nome }}</option>
                @endforeach
              </select>
            </div>
          </div>
          {{-- campo de captcha --}}
          @foreach ($form as $input)
            @if (is_array($input))
              @if (strpos($input[0], 'class="g-recaptcha"'))
                <div class="form-group row">
                  @foreach ($input as $element)
                    {!! $element !!}<br />
                  @endforeach
                </div>
              @endif
            @endif
          @endforeach
        @endif
      </div>
      @if ((!Auth::check()) || (session('perfil') == 'usuario'))
        <div class="text-right">
          <button type="submit" class="btn btn-primary">{{ ($modo == 'edit' ) ? 'Salvar' : 'Prosseguir' }}</button>
        </div>
      @endif
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      $('#form_principal_solicitacaoisencaotaxa').find(':input:visible:first').focus();

      $('#form_principal_solicitacaoisencaotaxa').each(function () {
        this.oninput = function(e) {
          e.target.setCustomValidity('');
        }
      });

      $('input[id="extras\[cpf\]"], input[id^="extras\[cpf_"]').each(function() {
        $(this).mask('000.000.000-00');
      });

      $('select[id="extras\[tipo_de_documento\]"]').change(function () {
        if ($(this).val() == 'passaporte') {
          $('#cpf_required').hide();
          $('input[id="extras\[cpf\]"]').removeAttr('required');
        } else {
          $('#cpf_required').show();
          $('input[id="extras\[cpf\]"]').attr('required', true);
        }
      });

      $('select[id="extras\[tipo_de_documento\]"]').trigger('change');
    });

    $('#form_principal_solicitacaoisencaotaxa').on('submit', function(event) {
      var form_valid = true;

      $('#form_principal_solicitacaoisencaotaxa [required]').each(function () {
        if (!this.validity.valid) {
          form_valid = false;
          switch (this.type) {
            case 'email':
              if (this.value !== '')
                return mostrar_validacao(this, 'E-mail inválido');
              else
                return mostrar_validacao(this, 'Favor preencher este campo');
            case 'radio':
              if ($('input[name="' + this.name + '"]:checked').length === 0)
                return mostrar_validacao(this, 'Favor selecionar uma opção');
              break;
            case 'checkbox':
              if (!this.checked)
                return mostrar_validacao(this, 'Favor marcar esta opção');
              break;
            default:
              if (this.value === '')
                return mostrar_validacao(this, 'Favor preencher este campo');
          }
        } else if ((this.id == 'extras[cpf]') || this.id.startsWith('extras[cpf_'))
          if (!validar_cpf(this.value)) {
            form_valid = false;
            return mostrar_validacao(this, 'CPF inválido');
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
