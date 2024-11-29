<?php

namespace App\Utils;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Spatie\Html\Facades\Html;

class JSONForms
{
    /**
     * Valida os campos do formulário
     *
     * @param $request Campos do formulário a serem validados
     * @param $selecao Seleção de onde vai pegar as regras de validação
     *
     * @return Array Contendo a validação
     */
    public static function buildRules($request, $selecao)
    {
        $template = json_decode($selecao->template);
        $validate = [];
        if ($template) {
            foreach ($template as $key => $json) {
                if (isset($json->validate)) {
                    $field = 'extras.' . $key;
                    $validate[$field] = $json->validate;
                }
            }
        }
        return $validate;
    }

    /**
     * Renderiza o formulário como array contendo html
     */
    protected static function JSON2Form($template, $data, $perfil)
    {
        $form = [];
        foreach ($template as $key => $json) {
            $input = [];
            $type = $json->type;
            $label = $template->$key->label;
            $html_string =
                '<div class="col-sm-3">' . PHP_EOL .
                  '<label class="col-form-label" for="extras[' . $key . ']">' . $label . '</label>' . PHP_EOL .
                '</div>' . PHP_EOL;
            $value = $data->$key ?? null;
            $required_string = ((isset($json->validate) && $json->validate) ? ' required' : '');

            switch ($type) {
                case 'select':
                    $json->value = JSONForms::simplifyTemplate($json->value);
                    $html_string .= '<div class="col-sm-9">' . PHP_EOL .
                                      '<select class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']"' . $required_string . '>' . PHP_EOL .
                                        '<option>Selecione um ..</option>' . PHP_EOL;
                    foreach ($json->value as $key => $option)
                        $html_string .= '<option value="' . $key . '"' . ($key == $value ? ' selected' : '') . '>' . $option . '</option>' . PHP_EOL;
                    $html_string .=   '</select>' . PHP_EOL .
                                    '</div>' . PHP_EOL;
                    break;

                case 'date':
                    $html_string .= '<div class="col-sm-2">' . PHP_EOL .
                                      '<input class="form-control datepicker hasDatePicker" name="extras[' . $key . ']" id="extras[' . $key . ']" type="text" value="' . $value . '"' . $required_string . '>' . PHP_EOL .
                                    '</div>' . PHP_EOL;
                    break;

                case 'radio':
                    $key0 = $key;
                    $json->value = JSONForms::simplifyTemplate($json->value);
                    $html_string  =   '<div class="col-sm-12 d-flex flex-column" style="gap: 10px;">' . PHP_EOL .
                                        $label . PHP_EOL;
                    foreach ($json->value as $key => $option)
                        $html_string .= '<div class="d-flex align-items-center gap-2">' . PHP_EOL .
                                          '&nbsp; &nbsp;' . PHP_EOL .
                                          '<input style="margin: 0; position: relative; top: -1px;" name="extras[' . $key0 . ']" id="extras[' . $key . ']" value="' . $key . '" type="radio"' . ($key == $value ? ' checked' : '') . '>' . PHP_EOL .
                                          '<label style="margin: 0; padding-left: 5px; position: relative; top: -2px;" for="extras[' . $key . ']">' . $option . '</label>' . PHP_EOL .
                                        '</div>' . PHP_EOL;
                    $html_string .=   '</div>' . PHP_EOL;
                    break;

                case 'checkbox':
                    $html_string  =   '<div class="col-sm-12 d-flex align-items-center" style="gap: 10px;">' . PHP_EOL .
                                        '<input class="form-control" style="width: auto; margin: 0;" name="extras[' . $key . ']" id="extras[' . $key . ']" type="checkbox"' . ($value == 'on' ? ' checked' : '') . $required_string . '>' . PHP_EOL .
                                        '<label style="margin: 0;" for="extras[' . $key . ']">' . $label . '</label>' . PHP_EOL .
                                      '</div>' . PHP_EOL;
                    break;

                case 'textarea':
                    $html_string .=   '<div class="col-sm-9">' . PHP_EOL .
                                        '<textarea class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']" rows="3">' . $value . '</textarea>' . PHP_EOL .
                                      '</div>' . PHP_EOL;
                    break;

                default:              // contempla os tipos text e number
                    $html_string .=   '<div class="col-sm-9">' . PHP_EOL .
                                        '<input class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']" type="' . $type . '" value="' . $value . '"' . $required_string . '>' . PHP_EOL .
                                      '</div>' . PHP_EOL;
                    break;
            }
            $input[] = new HtmlString($html_string);

            if (isset($json->help)) {
                $html_string =      '<div class="col-sm-3">&nbsp;</div>' . PHP_EOL .
                                    '<div class="col-sm-9">' . PHP_EOL .
                                      '<small class="form-text text-muted">' . $json->help . '</small>' . PHP_EOL .
                                    '</div>' . PHP_EOL;
                $input[] = new HtmlString($html_string);
            }

            # vamos incluir o input se "can for igual ao perfil" ou "se não houver can"
            if (($perfil && isset($json->can) && $json->can == $perfil) || (!$perfil && !isset($json->can)))
                $form[] = $input;
        }
        return $form;
    }

    /**
     * Trata as entradas para renderizar o formulário
     */
    public static function generateForm($selecao, $inscricao = null, $perfil = null)
    {
        $template = json_decode($selecao->template);
        $form = [];
        if ($template) {
            $data = $inscricao ? json_decode($inscricao->extras) : null;
            $form = JSONForms::JSON2Form($template, $data, $perfil);
        }
        return $form;
    }

    /**
     * Simplifica a estrutura do template do select
     */
    public static function simplifyTemplate($template)
    {
        $result = [];
        foreach ($template as $item) {
            $item = (array) $item;
            $key = removeAccents(Str::of($item['value'])->lower()->replace([' ', '-'], '_'));
            $result[$key] = $item['label'];
        }
        return json_decode(json_encode($result, true));
    }

    /**
     * Remove caracteres não aceitáveis no JSON
     */
    public static function fixJson($json)
    {
        // troca todo e qualquer \" por "
        $json = str_replace('\"', '"', json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        // " volta a ser \" se estivermos num contexto de <a href="...">...</a>
        // ou seja, <a href="...">...</a> se torna <a href=\"...\">...</a>
        $json = preg_replace('/<a\s+href\s*=\s*"/i', '<a href=\\"', $json);
        $json = preg_replace_callback('/<a\s+href\s*=\s*\\\".*?<\/a>/i', function ($matches) {
            return preg_replace('/">/', '\\">', $matches[0]);
        }, $json);

        return $json;
    }

    /*
     * Obtém o maior valor do dado campo no dado JSON
     */
    public static function getLastIndex($json, $field)
    {
        $lastIndex = -1;
        if ((!empty($json)) && (is_array($json)))
            foreach ($json as $item) {
                $item = (is_array($item) ? json_decode(json_encode($item)) : $item);
                $value = $item->$field;
                if (isset($value) && (!empty($value)) && is_numeric($value))
                    if ($value > $lastIndex)
                        $lastIndex = $value;
            }
        return $lastIndex;
    }

    /*
     * Ordena os campos do template, bem como os valores dos campos de tipo select e radio do template
     */
    public static function orderTemplate($template)
    {
        $template = json_decode($template, true);
        if (!empty($template)) {
            $ordered_template = array_column($template, 'order');
            array_multisort($ordered_template, SORT_ASC, $template);
            foreach ($template as &$field)
                if (!empty($field) && (($field['type'] == 'select') || ($field['type'] == 'radio'))) {
                    $ordered_templatevalue = array_column($field['value'], 'order');
                    array_multisort($ordered_templatevalue, SORT_ASC, $field['value']);
                }
        }
        return json_encode($template);
    }
}
