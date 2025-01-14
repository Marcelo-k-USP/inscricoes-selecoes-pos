$(document).ready(function() {
  function formatarHoraBrasil() {
    $('.timefield').each(function() {
      flatpickr(this, {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 1,
        allowInput: false,
        inline: true
      });
    });

    // aplica a cor de fundo do elemento '.form-group' aos elementos selecionados
    // não posso colocar fixo em css, pois essa cor varia conforme configuração do navegador
    $('.form-group .flatpickr-hour, .form-group .flatpickr-time-separator, .form-group .flatpickr-minute').css('color', $('.form-group').first().css('color') + ' !important');
  }

  formatarHoraBrasil();
});
