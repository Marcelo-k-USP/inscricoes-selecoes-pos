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

      $('#form_principal [required]').each(function () {
        this.oninvalid = function(e) {
          e.target.setCustomValidity('');
          if (!e.target.validity.valid)
            e.target.setCustomValidity('Favor preencher este campo');
        };
        this.oninput = function(e) {
          e.target.setCustomValidity('');
        }
      });

      $('input[id^="extras\[cpf"]').each(function() {
        $(this).mask('000.000.000-00');
      })

      $('input[id^="extras\[cep"]').each(function() {
        $(this).mask('00000-000');
      })

      $('input[id^="extras\[celular"]').each(function() {
        $(this).mask('(00) 00000-0000');
      })
    });
  </script>
@endsection
