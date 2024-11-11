<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Selecao extends Model
{
    use HasFactory;

    use \Glorand\Model\Settings\Traits\HasSettingsField;

    # seleções não segue convenção do laravel para nomes de tabela
    protected $table = 'selecoes';

    public $defaultSettings = [
        'instrucoes' => '',
    ];

    public $settingsRules = [
        'instrucoes' => 'nullable',
    ];

    # valores default na criação de nova seleção
    protected $attributes = [
        'estado' => 'Em elaboração',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'processo_id',
        'estado',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'processo_id',
            'label' => 'Processo',
            'type' => 'select',
            'model' => 'Processo',
            'data' => [],
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'descricao',
            'label' => 'Descrição',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        $fields = SELF::fields;
        foreach ($fields as &$field) {
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    /**
     * lista de estados padrão. Usado no factory.
     */
    public static function estados()
    {
        return ['Em elaboração', 'Em andamento', 'Encerrada'];
    }
    
    /**
     * config-status
     * obtem a lista de estados formatado para select
     */
    public function getStatusToSelect()
    {
        $status = $this->config->status;
        if ($status) {
            $out = ['Em Andamento' => 'Em andamento (sistema)'];
            foreach ($status as $item) {
                foreach ($item as $key => $value) {
                    if ($key == "label") {
                        $out[strtolower($value)] = $value;
                    }
                }
            }
            return $out;
        }
    }
    
    /**
     * Accessor getter para $config
     */
    public function getConfigAttribute($value)
    {
        $value = json_decode($value);

        $out = new \StdClass;
        $out->status = $value->status ?? config('selecoes.config.status');
        return $out;
    }
    
    /**
     * Accessor setter para $config
     */
    public function setConfigAttribute($value)
    {
        // quando este método é invocado pelo seeder, $value vem como string JSON
        // quando este método é invocado pelo MVC, $value vem como array

        if (is_string($value)) {
            $value_decoded = json_decode($value, true); // Decodifica como array associativo
            if (is_array($value_decoded) && (json_last_error() == JSON_ERROR_NONE)) {
                // se $value veio como string JSON, vamos utilizar $value_decoded, de modo a poder acessá-lo mais abaixo como array
                $value = $value_decoded;
            }
        }

        $config = new \StdClass;
        $config->status = $value['status'];
        $this->attributes['config'] = json_encode($config);
    }

    /**
     * Menu Seleções, lista as seleções
     *
     * @return coleção de seleções
     */
    public static function listarSelecoes()
    {
        return SELF::get();
    }

    /**
     * Mostra lista de processos e respectivas seleções
     * para selecionar e criar nova inscrição
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarSelecoesParaNovaInscricao()
    {
        # primeiro vamos pegar todas as seleções
        $processos = Processo::get();

        # e depois filtrar as que não pode
        foreach ($processos as &$processo) {
            # primeiro vamos pegar todas as seleções
            $selecoes = $processo->selecoes;

            # agora vamos remover as seleções onde não se pode inscrever
            # a ordem de liberação é relevante !!!!
            $selecoes = $selecoes->filter(function ($selecao, $key) {

                # bloqueia as seleções que não estão em andamento
                if ($selecao->estado != 'Em andamento') {
                    return false;
                }

                return true;
            });
            $processo->selecoes = $selecoes;
        }
        return $processos;
    }

    /**
     * Relacionamento: seleção pertence a processo
     */
    public function processo()
    {
        return $this->belongsTo('App\Models\Processo');
    }
}
