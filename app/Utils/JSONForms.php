<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
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
        if ($template)
            foreach ($template as $key => $json)
                if (isset($json->validate)) {
                    $field = 'extras.' . $key;
                    $validate[$field] = $json->validate;
                }
        return $validate;
    }

    /**
     * Renderiza o formulário como array contendo html
     */
    protected static function JSON2Form($selecao, $template, $data, $perfil, $classe_nome)
    {
        // em $template, tenho todos os campos do formulário de inscrição
        // a edição do formulário de uma seleção se refere sempre à edição do formulário de inscrição para essa seleção
        // no caso de solicitação de isenção de taxa em uma seleção, precisamos descartar vários campos, mantendo só os essenciais, e também incluir extraordinariamente o campo de motivo de isenção de taxa
        // esses campos essenciais para a solicitação de taxa são obrigatórios até na inscrição, e sua obrigatoriedade não pode ser mudada na edição do formulário da inscrição da seleção

        $form = [];
        foreach ($template as $key => $json) {
            $input = [];
            $type = $json->type;
            $value = $data->$key ?? null;

            // faz o controle de descartar campos que não entram no formulário de solicitação de isenção de taxa
            if (($classe_nome == 'Inscricao') || in_array($key, ['nome', 'tipo_de_documento', 'numero_do_documento', 'cpf', 'e_mail', 'motivo_isencao_taxa'])) {
                $required_attrib = '';
                $required_string = '';
                if (isset($json->validate) && $json->validate) {
                    $required_attrib = ' required';
                    $required_string = ' <small class="text-required"' . (in_array($key, ['cpf', 'uf_de_nascimento']) ? ' id="' . $key . '_required"' : '') . '>(*)</small>';
                }

                $label = $template->$key->label;
                $label_parts = explode (' ', $label);
                $label_last_word = array_pop($label_parts);
                $label_formatted = implode(' ', $label_parts) . ' <span style="white-space: nowrap;">' . $label_last_word . $required_string . '</span>';
                $html_string          =   '<div class="col-sm-3">' . PHP_EOL .
                                            '<label class="col-form-label va-middle" for="extras[' . $key . ']">' . $label_formatted . '</label> ' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                $html_string_linhapesquisa = '';
                $html_string_motivoisencaotaxa = '';
                $html_string_senha = '';

                switch ($type) {
                    case 'select':
                        $json->value = JSONForms::simplifyTemplate($json->value);
                        $html_string .=   '<div class="col-sm-9">' . PHP_EOL .
                                            '<select class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']"' . $required_attrib . '>' . PHP_EOL .
                                            '<option value="" disabled selected>Selecione...</option>' . PHP_EOL;
                        foreach ($json->value as $key => $option)
                            $html_string .=   '<option value="' . $key . '"' . ($key == $value ? ' selected' : '') . '>' . $option . '</option>' . PHP_EOL;
                        $html_string .=     '</select>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        break;

                    case 'date':
                        $html_string .=   '<div class="col-sm-2">' . PHP_EOL .
                                            '<input class="form-control datepicker hasDatePicker" name="extras[' . $key . ']" id="extras[' . $key . ']" type="text" value="' . $value . '"' . $required_attrib . '>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        break;

                    case 'radio':
                        $key0 = $key;
                        $json->value = JSONForms::simplifyTemplate($json->value);
                        $html_string  =   '<div class="col-sm-12 d-flex flex-column" style="gap: 10px;">' . PHP_EOL .
                                            '<div class="d-flex align-items-center">' . PHP_EOL .
                                            $label . '&nbsp;' . $required_string . PHP_EOL .
                                            '</div>' . PHP_EOL;
                        $primeiro_item = true;
                        foreach ($json->value as $key => $option) {
                            $html_string .= '<div class="d-flex align-items-center gap-2">' . PHP_EOL .
                                            '&nbsp; &nbsp;' . PHP_EOL .
                                            '<input style="margin: 0; position: relative; top: -1px;" name="extras[' . $key0 . ']" id="extras[' . $key0 . '_' . $key . ']" value="' . $key . '" type="radio"' . ($key == $value ? ' checked' : '') . ($primeiro_item ? $required_attrib : '') . '>' . PHP_EOL .
                                            '<label style="margin: 0; padding-left: 5px; position: relative; top: -2px;" for="extras[' . $key0 . '_' . $key . ']">' . $option . '</label>' . PHP_EOL .
                                            '</div>' . PHP_EOL;
                            $primeiro_item = false;
                        }
                        $html_string .=   '</div>' . PHP_EOL;
                        break;

                    case 'checkbox':
                        $html_string  =   '<div class="col-sm-12 d-flex align-items-center" style="gap: 10px;">' . PHP_EOL .
                                            '<input class="form-control" style="width: auto; margin: 0;" name="extras[' . $key . ']" id="extras[' . $key . ']" type="checkbox"' . ($value == 'on' ? ' checked' : '') . $required_attrib . '>' . PHP_EOL .
                                            '<label style="margin: 0;" for="extras[' . $key . ']">' . $label . ' ' . $required_string . '</label> ' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        break;

                    case 'textarea':
                        $html_string .=   '<div class="col-sm-9">' . PHP_EOL .
                                            '<textarea class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']" rows="3"' . $required_attrib . '>' . $value . '</textarea>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        break;

                    case 'label':
                        $html_string  =   '<div class="col-sm-12">' . PHP_EOL .
                                            '<label class="col-form-label va-middle" for="extras[' . $key . ']">' . $label_formatted . '</label> ' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        break;

                    default:              // contempla os tipos text, number e email
                        $largura = 9;
                        $html_string_adicional = '';
                        if (($key == 'cep') || (strpos($key, 'cep_') === 0)) {
                            $largura = 2;
                            $html_string_adicional .= '<a href="javascript:void(0);" onclick="consultar_cep(\'' . $key . '\')" id="consultar_' . $key . '" class="btn btn-primary">Consultar CEP</a>';
                        }
                        $html_string .=   '<div class="col-sm-' . $largura . '">' . PHP_EOL .
                                            '<input class="form-control w-100" name="extras[' . $key . ']" id="extras[' . $key . ']" type="' . $type . '" value="' . $value . '"' . $required_attrib . '>' . PHP_EOL .
                                          '</div>' . PHP_EOL .
                                          $html_string_adicional;
                        if (($key == 'e_mail') && !Auth::check())
                            $html_string_senha .=
                                          '<div class="col-sm-3" style="margin-top: -20px;">' . PHP_EOL .
                                            '<label class="col-form-label va-middle" for="password">Senha <small class="text-required">(*)</small></label> ' . PHP_EOL .
                                          '</div>' . PHP_EOL .
                                          '<div class="col-sm-3" style="margin-top: -20px;">' . PHP_EOL .
                                            '<input class="form-control" style="width: 100%; padding-right: 30px" name="password" id="password" type="password" required>' . PHP_EOL .
                                            '<a href="javascript:void(0);" onclick="toggle_password(\'password\')" style="position: absolute; right: 24px; top: 20%; text-decoration: none;">' . PHP_EOL .
                                              '<img src="' . url('/icons/view.png') . '" id="toggle_icon_password" style="width: 20px; height: 20px;">' . PHP_EOL .
                                            '</a>' . PHP_EOL .
                                          '</div>' . PHP_EOL .
                                          '<div id="strength-wrapper">' . PHP_EOL .
                                            '<div id="barra_forca_password" style="height: 10px; width: 0px;">&nbsp;</div>' . PHP_EOL .
                                            '<p id="texto_forca_password" style="margin-top: 5px;">&nbsp;</p>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        if (($key == 'nome') && ($classe_nome == 'Inscricao') && ($selecao->categoria->nome !== 'Aluno Especial')) {
                            $html_string_linhapesquisa .=
                                          '<div class="col-sm-3">' . PHP_EOL .
                                            '<label class="col-form-label va-middle" for="extras[linha_pesquisa]">Linha de <span style="white-space: nowrap;">Pesquisa/Tema <small class="text-required">(*)</small></span></label>' . PHP_EOL .
                                          '</div>' . PHP_EOL .
                                          '<div class="col-sm-9">' . PHP_EOL .
                                            '<select class="form-control w-100" name="extras[linha_pesquisa]" id="extras[linha_pesquisa]" required>' . PHP_EOL .
                                              '<option value="" disabled selected>Selecione...</option>' . PHP_EOL;
                            foreach ($selecao->niveislinhaspesquisa as $nivellinhapesquisa)
                                if ($nivellinhapesquisa->nivel_id == $data->nivel)
                                    $html_string_linhapesquisa .=
                                              '<option value="' . $nivellinhapesquisa->linhapesquisa_id . '"' . ((isset($data->linha_pesquisa) && ($nivellinhapesquisa->linhapesquisa_id == $data->linha_pesquisa)) ? ' selected' : '') . '>' . $nivellinhapesquisa->linhapesquisa->nome . '</option>' . PHP_EOL;
                            $html_string_linhapesquisa .=
                                            '</select>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        }
                        if (($key == 'nome') && ($classe_nome == 'SolicitacaoIsencaoTaxa')) {
                            $html_string_motivoisencaotaxa .=
                                          '<div class="col-sm-3">' . PHP_EOL .
                                            '<label class="col-form-label va-middle" for="extras[motivo_isencao_taxa]">Motivo <small class="text-required">(*)</small></label>' . PHP_EOL .
                                          '</div>' . PHP_EOL .
                                          '<div class="col-sm-9">' . PHP_EOL .
                                            '<select class="form-control w-100" name="extras[motivo_isencao_taxa]" id="extras[motivo_isencao_taxa]" required>' . PHP_EOL .
                                              '<option value="" disabled selected>Selecione...</option>' . PHP_EOL;
                            foreach ($selecao->motivosisencaotaxa as $motivoisencaotaxa)
                                $html_string_motivoisencaotaxa .=
                                              '<option value="' . $motivoisencaotaxa->id . '"' . ((isset($data->motivo_isencao_taxa) && ($motivoisencaotaxa->id == $data->motivo_isencao_taxa)) ? ' selected' : '') . '>' . $motivoisencaotaxa->nome . '</option>' . PHP_EOL;
                            $html_string_motivoisencaotaxa .=
                                            '</select>' . PHP_EOL .
                                          '</div>' . PHP_EOL;
                        }
                }

                // fora do fluxo normal: inclui campo de linha de pesquisa, pois ele não fica no $template
                // este campo é inserido antes do nome
                if ($html_string_linhapesquisa != '')
                    $form[] = [new HtmlString($html_string_linhapesquisa)];

                // fora do fluxo normal: inclui campo de motivo de isenção de taxa, pois ele não fica no $template
                // este campo é inserido antes do nome
                if ($html_string_motivoisencaotaxa != '')
                    $form[] = [new HtmlString($html_string_motivoisencaotaxa)];

                // fluxo normal: prepara para incluir o campo propriamente dito
                $input[] = new HtmlString($html_string);

                // fluxo normal: prepara para incluir help
                if (isset($json->help)) {
                    $html_string =        '<div class="col-sm-3">&nbsp;</div>' . PHP_EOL .
                                        '<div class="col-sm-9">' . PHP_EOL .
                                            '<small class="form-text text-muted">' . $json->help . '</small>' . PHP_EOL .
                                        '</div>' . PHP_EOL;
                    $input[] = new HtmlString($html_string);
                }

                // fluxo normal: inclui o campo propriamente dito, desde que "can for igual ao perfil" ou "se não houver can"
                if (($perfil && isset($json->can) && $json->can == $perfil) || (!$perfil && !isset($json->can)))
                    $form[] = $input;

                // fora do fluxo normal: inclui campo de senha, pois ele não fica no $template
                // este campo é inserido depois do e-mail
                if ($html_string_senha != '')
                    $form[] = [new HtmlString($html_string_senha)];
            }
        }

        return $form;
    }

    /**
     * Trata as entradas para renderizar o formulário
     */
    public static function generateForm($selecao, string $classe_nome, $objeto = null, $perfil = null)
    {
        $template = json_decode($selecao->template);
        $form = [];
        if ($template) {
            $data = $objeto ? json_decode($objeto->extras) : null;
            $form = JSONForms::JSON2Form($selecao, $template, $data, $perfil, $classe_nome);
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
