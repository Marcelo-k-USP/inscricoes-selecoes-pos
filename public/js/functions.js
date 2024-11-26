/* @autor uspdev/alecosta 10/02/2022
* Função que ordena de form alfabética as opções de um campo caixa de seleção adicionado à seleção
*/
function ordenarOpcoes(campo) {
    // Get all options from select field
    var options = $('select[name="extras[' + campo + ']"] option');
    var arr = options.map(function(_, o) {
        return {
        t: $(o).text(),
        v: o.value,
        s: $(o).attr('selected')
        };
    }).get();
    // search for 'Outros' item
    var has_outros = false, index = 0, outros_index, outros_item;
    arr.forEach((e) => {
      if (e.t.toLowerCase() == 'outros') {
        has_outros = true;
        outros_index = index;
      }
      index++;
    });
    // remove 'Outros' item from the middle
    if (has_outros) {
      outros_item = arr[outros_index];
      arr.splice(outros_index, 1);
    }
    // Sort alphabetic order
    arr.sort(function(o1, o2) {
        return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
    });
    // readds 'Outros' item at the end
    if (has_outros)
      arr.push(outros_item);
    options.each(function(i, o) {
        o.value = arr[i].v;
        $(o).text(arr[i].t);
        $(o).attr('selected', arr[i].s);
    });
    // Set to first option Selecione... ou Escolha um..., onde value="" e selected
    var valFirstOption = options.first().val();
    $('select[name="extras[' + campo + ']"] option[value=""]').insertBefore('select[name="extras[' + campo + ']"] option[value="' + valFirstOption + '"]');
}

/* @autor uspdev/alecosta 10/02/2022
* Função que verifica se o tipo de campo adicionado na seleção é caixa de seleção
* Se for, muda tipo tipo de campo valor de input para textarea
*/
function mudarCampoInputTextarea(campo) {
	var fieldTypeSelect = $('select[name="' + campo + '"]').find(":selected").val();
  // se é caixa de seleção, muda o campo valor para textarea
  if (fieldTypeSelect == 'select') {
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
