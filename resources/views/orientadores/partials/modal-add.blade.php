<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#OrientadorModal">
  <i class="fas fa-plus"></i> Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="OrientadorModal" data-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar Orientador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{ html()->form('post', $inclusor_url . '/' . $inclusor_objeto->id . '/orientadores')->open() }}
            @csrf
            @method('post')
            {{ html()->hidden('id') }}
            @php
              $fields = $fields_orientador;
            @endphp
            <div class="form-group row">
              <div class="col-sm-12 d-flex align-items-center" style="gap: 10px;">
                <input class="form-control" style="width: auto; margin: 0;" name="externo" id="externo" type="checkbox" onclick="toggle_externo()">
                <label style="margin: 0;" for="externo">Externo à unidade</label>
              </div>
            </div>
            <div id="grupo_interno">
              @foreach ($fields as $col)
                @if ($col['name'] == 'codpes')
                  @include('common.list-table-form-pessoa')
                @endif
              @endforeach
            </div>
            <div id="grupo_externo" style="display: none;">
              <div class="form-group row">
                {{ html()->label('Nome', 'externo_nome')->class('col-form-label col-sm-3') }}
                <div class="col-sm-9">
                  {{ html()->input('text', 'externo_nome')->class('form-control') }}
                </div>
              </div>
              <div class="form-group row">
                {{ html()->label('Número USP', 'externo_codpes')->class('col-form-label col-sm-3') }}
                <div class="col-sm-9">
                  {{ html()->input('text', 'externo_codpes')->class('form-control')->attribute('oninput', 'validateInteger(this)') }}
                </div>
              </div>
              <div class="form-group row">
                {{ html()->label('E-mail', 'externo_email')->class('col-form-label col-sm-3') }}
                <div class="col-sm-9">
                  {{ html()->input('text', 'externo_email')->class('form-control') }}
                </div>
              </div>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          {{ html()->form()->close() }}
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script src="js/functions.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      var modalForm = $('#OrientadorModal');
      var $oSelect2 = modalForm.find(':input[name^="codpes"]');
      $oSelect2.select2({
        ajax: {
          url: params => 'search/' + ($.isNumeric(params.term) ? 'codpes' : 'partenome'),
            dataType: 'json',
            delay: 1000,
            data: params => ({
              term: params.term,
              tipvinext: 'Docente'
            })
          },
          dropdownParent: modalForm,
          minimumInputLength: 4,
          theme: 'bootstrap4',
          width: '100%',
          language: 'pt_br'
      });

      // coloca o focus no select2
      // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });
    });

    function toggle_externo()
    {
      if ($('#externo').is(':checked')) {
        $('#grupo_interno').hide();
        $('#grupo_externo').show();
      } else {
        $('#grupo_interno').show();
        $('#grupo_externo').hide();
      }
    }
  </script>
@endsection
