@section('styles')
@parent
  <style>
    #card-selecao-principal {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

{{ html()->form('post', $data->url . (($modo == 'edit') ? ('/edit/' . $inscricao->id) : '/create'))
  ->attribute('id', 'form_principal')
  ->attribute('novalidate', '')          // pois faço minha validação manual em $('#form_principal').on('submit'
  ->open() }}
  @csrf
  @method($modo == 'edit' ? 'put' : 'post')
  {{ html()->hidden('id') }}
  <input type="hidden" id="selecao_id" name="selecao_id" value="{{ $inscricao->selecao->id }}">
  <div class="card mb-3 w-100" id="card-selecao-principal">
    <div class="card-header">
      Informações básicas
    </div>
    <div class="card-body">
      <div class="list_table_div_form">
        @if (isset($form))
          @foreach ($form as $input)
            <div class="form-group row">
              @if (is_array($input))
                @foreach ($input as $element)
                  {!! $element !!}
                @endforeach
                <br>
              @endif
            </div>
          @endforeach
        @endif
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">{{ ($modo == 'edit' ) ? 'Salvar' : 'Prosseguir' }}</button>
      </div>
    </div>
  </div>
{{ html()->form()->close() }}

@section('javascripts_bottom')
@parent
  <script>
    $(document).ready(function() {

      $('#form_principal').find(':input:visible:first').focus();

      $('#form_principal').each(function () {
        this.oninput = function(e) {
          e.target.setCustomValidity('');
        }
      });

      $('input[id="extras\[cpf\]"], input[id^="extras\[cpf_"]').each(function() {
        $(this).mask('000.000.000-00');
      });

      $('input[id="extras\[cep\]"], input[id^="extras\[cep_"]').each(function() {
        $(this).mask('00000-000');
      });

      $('input[id="extras\[celular\]"], input[id^="extras\[celular_"]').each(function() {
        $(this).mask('(00) 00000-0000');
      });
    });

    $('#form_principal').on('submit', function(event) {
      var form_valid = true;
      $('#form_principal [required]').each(function () {
        if (!this.validity.valid) {
          form_valid = false;
          if (this.type === 'email')
            if (this.value !== '')
              return mostrar_validacao(this, 'E-mail inválido');
            else
              return mostrar_validacao(this, 'Favor preencher este campo');
          else if (this.value === '')
            return mostrar_validacao(this, 'Favor preencher este campo');
        } else if ((this.id == 'extras[cpf]') || this.id.startsWith('extras[cpf_'))
          if (!validar_cpf(this.value)) {
            form_valid = false;
            return mostrar_validacao(this, 'CPF inválido');
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

    function consultar_cep(field_name)
    {
      var cep = $('input[id="extras\[' + field_name + '\]"]').val().replace('-', '');
      if (cep)
        $('#consultar_' + field_name).text('Consultando ...');
        $.ajax({
          url: '{{ route("consulta.cep") }}',
          type: 'get',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            cep: cep
          },
          success: function(data) {
            var field_suffix = '';
            if (field_name.includes('_'))
              field_suffix = '_' + field_name.split('_')[1];

            $('input[id="extras\[endereco_residencial' + field_suffix + '\]"]').val(data.logradouro);
            $('input[id="extras\[bairro' + field_suffix + '\]"]').val(data.bairro);
            $('input[id="extras\[cidade' + field_suffix + '\]"]').val(data.localidade);
            $('select[id="extras\[uf' + field_suffix + '\]"]').val(data.uf.toLowerCase());

            $('#consultar_' + field_name).text('Consultar CEP');
          },
          error: function(xhr, status, error) {
            $('#consultar_' + field_name).text('Consultar CEP');

            if (xhr.responseJSON && xhr.responseJSON.error)
              window.alert(xhr.responseJSON.error);
            else if (xhr.responseText)
              window.alert(xhr.responseText);
          }
      });
    }

    function validar_cpf(cpf)
    {
      cpf = cpf.replace(/\./g, '').replace('-', '');
      if (cpf.length != 11)
        return false;

      var resto;
      var soma;

      soma = 0;
      for (var i = 1; i <= 9; i++)
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      resto = (soma * 10) % 11;
      if ((resto == 10) || (resto == 11))
        resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10)))
        return false;

      soma = 0;
      for (var i = 1; i <= 10; i++)
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      resto = (soma * 10) % 11;
      if ((resto == 10) || (resto == 11))
        resto = 0;
      if (resto !== parseInt(cpf.substring(10, 11)))
        return false;

      return true;
    }
  </script>
@endsection
