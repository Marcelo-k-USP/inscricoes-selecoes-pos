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

function toggle_senha() {
  var toggle_icon = $('#toggle_icon');
  var input_senha = $('#senha');
  if (input_senha.length === 0)
    input_senha = $('#password');
  toggle_icon.attr('src', '/icons/' + (input_senha.attr('type') === 'password' ? 'hide' : 'view') + '.png');
  input_senha.attr('type', (input_senha.attr('type') === 'password' ? 'text' : 'password'));
}
