$(document).ready(function() {
  $('.timefield').each(function() {
    flatpickr(this, {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      defaultHour: null,
      defaultMinute: null,
      time_24hr: true,
      minuteIncrement: 1,
      allowInput: false,
      inline: true,
      onReady: function(selectedDates, dateStr, instance) {
        // habilita navegação para dentro dos campos do flatpickr via <TAB> e <SHIFT> + <TAB>
        $(instance.calendarContainer).find('.flatpickr-time .flatpickr-hour, .flatpickr-time .flatpickr-minute').removeAttr('tabindex');
      }
    });
  });

  // aplica a cor de fundo do elemento '.form-group' aos elementos selecionados
  // não posso colocar fixo em css, pois essa cor varia conforme configuração do navegador
  $('.form-group .flatpickr-hour, .form-group .flatpickr-time-separator, .form-group .flatpickr-minute').css('color', $('.form-group').first().css('color') + ' !important');
});

// habilita navegação para fora dos campos do flatpickr via <TAB> e <SHIFT> + <TAB>
$(document).on('keydown', '.flatpickr-hour, .flatpickr-minute', function(e) {
  const currentClass = $(this).attr('class');
  const allInputs = $(this).closest('form').find('input');
  const currentIndex = allInputs.index(this);

  if (e.key === 'Tab' && (!e.shiftKey) && (currentClass == 'numInput flatpickr-minute')) {
    e.preventDefault();
    allInputs.eq(currentIndex + 1).focus();    // move para o próximo elemento
  } else if (e.key === 'Tab' && e.shiftKey && (currentClass == 'numInput flatpickr-hour')) {
    e.preventDefault();
    allInputs.eq(currentIndex - 2).focus();    // move para o elemento anterior (se eu colocasse - 1, cairia no <input type="text" style="display: none;">... preciso pulá-lo, por isso - 2)
  }
});
