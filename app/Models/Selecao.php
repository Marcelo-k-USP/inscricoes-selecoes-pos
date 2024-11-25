<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Selecao extends Model
{
    use HasFactory;

    use \Glorand\Model\Settings\Traits\HasSettingsField;

    # selecoes não segue convenção do laravel para nomes de tabela
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
        'template' => '{
            "nome": {
                "label": "Nome",
                "type": "text",
                "required": true
            }
        }',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'categoria_id',
        'programa_id',
        'estado',
        'template',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'categoria_id',
            'label' => 'Categoria',
            'type' => 'select',
            'model' => 'Categoria',
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
        [
            'name' => 'data_inicio',
            'label' => 'Início',
            'type' => 'date',
        ],
        [
            'name' => 'data_fim',
            'label' => 'Fim',
            'type' => 'date',
        ],
        [
            'name' => 'programa_id',
            'label' => 'Programa',
            'type' => 'select',
            'model' => 'Programa',
            'data' => [],
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        $fields = SELF::fields;
        foreach ($fields as &$field)
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        return $fields;
    }

    /**
     * template
     * retorna os campos do template do formulario
     */
    public static function getTemplateFields()
    {
        return ['label', 'type', 'can', 'help', 'value', 'validate'];
    }
    
    /**
     * retorna todas as seleções autorizadas para o usuário
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $selecoes = SELF::get();
        $ret = [];
        foreach ($selecoes as $selecao)
            if (Gate::allows('selecoes.view', $selecao))
                $ret[$selecao->id] = $selecao->nome . ' (' . $selecao->categoria->nome . ')';
        return $ret;
    }

    /**
     * Retorna os tipos de arquivo possíveis na seleção.
     */
    public static function tiposArquivo()
    {
        return ['Edital', 'Normas para Isenção de Taxa'];
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
     * Accessor para $template
     */
    public function getTemplateAttribute($value)
    {
        return (empty($value)) ? '{}' : $value;
    }
    
    /**
     * Menu Seleções, lista as seleções
     *
     * @return coleção de seleções
     */
    public static function listarSelecoes()
    {
        self::atualizaStatusSelecoes();
        return SELF::get();
    }

    /**
     * Mostra lista de categorias e respectivas seleções
     * para selecionar e criar nova inscrição
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarSelecoesParaNovaInscricao()
    {
        self::atualizaStatusSelecoes();
        
        $categorias = Categoria::get();                                  // primeiro vamos pegar todas as seleções
        foreach ($categorias as $categoria) {                            // e depois filtrar as que não pode
            $selecoes = $categoria->selecoes;                            // primeiro vamos pegar todas as seleções
            $selecoes = $selecoes->filter(function ($selecao, $key) {    // agora vamos remover as seleções onde não se pode inscrever... a ordem de liberação é relevante!
                if ($selecao->estado != 'Em andamento')                  // bloqueia as seleções que não estão em andamento
                    return false;
                return true;
            });
            $categoria->selecoes = $selecoes;
        }
        return $categorias;
    }

    public static function atualizaStatusSelecoes()
    {
        $hoje = Carbon::today();
        SELF::where('data_inicio', '>', $hoje)
            ->where('estado', '<>', 'Em elaboração')
            ->update(['estado' => 'Em elaboração']);
        SELF::where('data_inicio', '<=', $hoje)
            ->where('data_fim', '>=', $hoje)
            ->where('estado', '<>', 'Em andamento')
            ->update(['estado' => 'Em andamento']);
        SELF::where('data_fim', '<', $hoje)
            ->where('estado', '<>', 'Encerrada')
            ->update(['estado' => 'Encerrada']);
    }

    /**
     * relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->belongsToMany('App\Models\Arquivo', 'arquivo_selecao')->withPivot('tipo')->withTimestamps();
    }
    
    /**
     * relacionamento com linhas de pesquisa
     */
    public function linhaspesquisa()
    {
        return $this->belongsToMany('App\Models\LinhaPesquisa', 'linhapesquisa_selecao', 'selecao_id', 'linhapesquisa_id')->withTimestamps();
    }

    /**
     * Relacionamento: seleção pertence a categoria
     */
    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }

    /**
     * Relacionamento: seleção pertence a programa
     */
    public function programa()
    {
        return $this->belongsTo('App\Models\Programa');
    }
}
