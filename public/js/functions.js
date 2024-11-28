/* @autor uspdev/alecosta 10/02/2022
* Função que verifica se o tipo de campo adicionado na seleção é caixa de seleção
* Se for, muda tipo tipo de campo valor de input para textarea
*/
function mudarCampoInputTextarea(campo) {
	var fieldTypeSelect = $('select[name="' + campo + '"]').find(":selected").val();
  // se é caixa de seleção, muda o campo valor para textarea
  if ((fieldTypeSelect == 'select') || (fieldTypeSelect == 'radio')) {
    $('input[name="' + campo.replace('][type]', '][value]') + '"]').each(function () {
      var classe = $(this).attr('class');
      var style = $(this).attr('style');
      var name = $(this).attr('name');
      var value = $(this).val();
      var textbox = $(document.createElement('textarea'));
      textbox.attr('class', classe);
      textbox.attr('name', name);
      textbox.attr('style', style);
      textbox.val(value);
      $(this).replaceWith(textbox);
    });
	// do contrário, volta o campo valor para input
  } else {
      $('textarea[name="' + campo.replace('][type]', '][value]') + '"]').each(function () {
      var classe = $(this).attr('class');
      var style = $(this).attr('style');
      var name = $(this).attr('name');
      var value = $(this).val();
      var inputbox = $(document.createElement('input'));
      inputbox.attr('class', classe);
      inputbox.attr('name', name);
      inputbox.attr('style', style);
      inputbox.val(value);
      $(this).replaceWith(inputbox);
    });
  }
}
