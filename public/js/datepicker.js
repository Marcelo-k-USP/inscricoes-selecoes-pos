$('.datepicker').datepicker({
  format: 'dd/mm/yyyy',
  language: 'pt-BR',
  onSelect: function (dateText, inst) {
    $(this).datepicker('hide');
  }
});
