<button type="button" class="btn btn-sm btn-outline-primary btn-adicionar-codpes py-0 ml-2">
  <i class="fas fa-plus"></i>
</button>

@once
@section('javascripts_bottom')
  @parent
  <div class="modal fade" id="modal-adicionar-codpes" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Adicionar Pessoa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form" data-ajax="{{ route('SenhaunicaFindUsers') }}">
            <div class="mb-3">
              <select name="add_codpes" class="form-control form-control-sm">
                <option value="0">Digite o nome ou codpes..</option>
              </select>
            </div>
            <div>
              <div class="float-right">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-sm btn-primary btn-submit">Salvar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      var senhaunicaUserModal = $('#modal-adicionar-codpes')
      var $oSelect2 = senhaunicaUserModal.find(':input[name=add_codpes]')
      var dataAjax = senhaunicaUserModal.find('.form').data('ajax')
      var formId;

      $('.btn-adicionar-codpes').on('click', function() {
        senhaunicaUserModal.modal()
        formId = $(this).closest('form').attr('id')
      })

      senhaunicaUserModal.find('.btn-submit').on('click', function() {
        let codpes = senhaunicaUserModal.find(':input[name=add_codpes]').val()
        let codpes_input_add = $('<input type="hidden" name="add_codpes" value="' + codpes + '">')
        $('#' + formId).append(codpes_input_add)
        // o submit nao funcionou em disciplinas.edit então fizémos um click no botão de submit
        $('#' + formId).trigger('submit')
        $('#' + formId).find('.btn-submit-default').trigger('click')
      })

      // abre o select2 automaticamente
      senhaunicaUserModal.on('shown.bs.modal', function() {
        $oSelect2.select2('open')
      })

      // coloca o focus no select2
      // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });

      $oSelect2.select2({
        ajax: {
          url: dataAjax,
          dataType: 'json',
          delay: 1000
        },
        dropdownParent: senhaunicaUserModal,
        minimumInputLength: 4,
        theme: 'bootstrap4',
        width: 'resolve',
        language: 'pt-BR'
      })
    })
  </script>
@endsection
@endonce
