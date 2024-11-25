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
            $html_string = '<label class="col-form-label col-sm-2" for="extras[' . $key . ']">' . $label . '</label>' . PHP_EOL;
            $value = $data->$key ?? null;

            switch ($type) {
                //caso seja um select passa o valor padrao
                case 'select':
                    $json->value = JSONForms::simplifyTemplate($json->value);
                    $html_string .= '<div class="col-sm-5">' . PHP_EOL .
                                      '<select class="form-control" name="extras[' . $key . ']" id="extras[' . $key . ']">' . PHP_EOL .
                                        '<option value="selected">Selecione um ..</option>' . PHP_EOL;
                    foreach ($json->value as $option)
                        $html_string .= '<option value="' . $option[0] . '">' . $option[1] . '</option>' . PHP_EOL;
                    $html_string .=   '</select>' . PHP_EOL .
                                    '</div>';
                    break;

                default:
                    $html_string .= '<input class="col-form-label col-sm-5" name="extras[' . $key . ']" id="extras[' . $key . ']" type="' . $type . '">' . PHP_EOL;
                    break;
            }
            $input[] = new HtmlString($html_string);

            if (isset($json->help))
                $input[] = new HtmlString('<small class="form-text text-muted">' . $json->help . '</small>');

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
        return str_replace('\"', '"', json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
