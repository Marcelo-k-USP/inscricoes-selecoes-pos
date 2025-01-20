<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Selecao extends Model
{
    use \Glorand\Model\Settings\Traits\HasSettingsField;
    use HasFactory;

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
        'estado' => 'Aguardando Documentação',
        'template' => '{
            "nome": {
                "label": "Nome",
                "type": "text",
                "validate": "required",
                "order": 0
            },
            "nome_social": {
                "label": "Nome Social",
                "type": "text",
                "help": "Decreto Estadual n. 55.588, de 17/03/2010",
                "order": 1
            },
            "tipo_de_documento": {
                "label": "Tipo de Documento",
                "type": "select",
                "value": [
                    {
                        "label": "RG",
                        "value": "rg",
                        "order": 0
                    },
                    {
                        "label": "RNE",
                        "value": "rne",
                        "order": 1
                    },
                    {
                        "label": "Passaporte",
                        "value": "passaporte",
                        "order": 2
                    }
                ],
                "help": "Utilize o passaporte apenas se não possuir documento de identidade brasileira (RG)",
                "validate": "required",
                "order": 2
            },
            "numero_do_documento": {
                "label": "Número do Documento",
                "type": "text",
                "validate": "required",
                "order": 3
            },
            "data_vencto_passaporte": {
                "label": "Data de Vencimento do Passaporte",
                "type": "date",
                "order": 4
            },
            "cpf": {
                "label": "CPF",
                "type": "text",
                "validate": "required",
                "order": 5
            },
            "titulo_de_eleitor": {
                "label": "Título de Eleitor",
                "type": "text",
                "order": 6
            },
            "documento_militar": {
                "label": "Documento Militar",
                "type": "text",
                "help": "Quando pertinente",
                "order": 7
            },
            "nome_da_mae": {
                "label": "Nome da Mãe",
                "type": "text",
                "validate": "required",
                "order": 8
            },
            "nome_do_pai": {
                "label": "Nome do Pai",
                "type": "text",
                "order": 9
            },
            "data_de_nascimento": {
                "label": "Data de Nascimento",
                "type": "date",
                "validate": "required",
                "order": 10
            },
            "local_de_nascimento": {
                "label": "Local de Nascimento",
                "type": "text",
                "validate": "required",
                "order": 11
            },
            "uf_de_nascimento": {
                "label": "UF de Nascimento",
                "type": "select",
                "value": [
                    {
                        "label": "AC",
                        "value": "ac",
                        "order": 0
                    },
                    {
                        "label": "AL",
                        "value": "al",
                        "order": 1
                    },
                    {
                        "label": "AM",
                        "value": "am",
                        "order": 2
                    },
                    {
                        "label": "AP",
                        "value": "ap",
                        "order": 3
                    },
                    {
                        "label": "BA",
                        "value": "ba",
                        "order": 4
                    },
                    {
                        "label": "CE",
                        "value": "ce",
                        "order": 5
                    },
                    {
                        "label": "DF",
                        "value": "df",
                        "order": 6
                    },
                    {
                        "label": "ES",
                        "value": "es",
                        "order": 7
                    },
                    {
                        "label": "GO",
                        "value": "go",
                        "order": 8
                    },
                    {
                        "label": "MA",
                        "value": "ma",
                        "order": 9
                    },
                    {
                        "label": "MG",
                        "value": "mg",
                        "order": 10
                    },
                    {
                        "label": "MS",
                        "value": "ms",
                        "order": 11
                    },
                    {
                        "label": "MT",
                        "value": "mt",
                        "order": 12
                    },
                    {
                        "label": "PA",
                        "value": "pa",
                        "order": 13
                    },
                    {
                        "label": "PB",
                        "value": "pb",
                        "order": 14
                    },
                    {
                        "label": "PE",
                        "value": "pe",
                        "order": 15
                    },
                    {
                        "label": "PI",
                        "value": "pi",
                        "order": 16
                    },
                    {
                        "label": "PR",
                        "value": "pr",
                        "order": 17
                    },
                    {
                        "label": "RJ",
                        "value": "rj",
                        "order": 18
                    },
                    {
                        "label": "RN",
                        "value": "rn",
                        "order": 19
                    },
                    {
                        "label": "RO",
                        "value": "ro",
                        "order": 20
                    },
                    {
                        "label": "RR",
                        "value": "rr",
                        "order": 21
                    },
                    {
                        "label": "RS",
                        "value": "rs",
                        "order": 22
                    },
                    {
                        "label": "SC",
                        "value": "sc",
                        "order": 23
                    },
                    {
                        "label": "SE",
                        "value": "se",
                        "order": 24
                    },
                    {
                        "label": "SP",
                        "value": "sp",
                        "order": 25
                    },
                    {
                        "label": "TO",
                        "value": "to",
                        "order": 26
                    }
                ],
                "validate": "required",
                "order": 12
            },
            "sexo": {
                "label": "Sexo",
                "type": "select",
                "value": [
                    {
                        "label": "Masculino",
                        "value": "masculino",
                        "order": 0
                    },
                    {
                        "label": "Feminino",
                        "value": "feminino",
                        "order": 1
                    },
                    {
                        "label": "Não Binário",
                        "value": "nao_binario",
                        "order": 2
                    }
                ],
                "validate": "required",
                "order": 13
            },
            "raca_cor": {
                "label": "Raça/Cor",
                "type": "select",
                "value": [
                    {
                        "label": "Amarela",
                        "value": "amarela",
                        "order": 0
                    },
                    {
                        "label": "Branca",
                        "value": "branca",
                        "order": 1
                    },
                    {
                        "label": "Indígena",
                        "value": "indigena",
                        "order": 2
                    },
                    {
                        "label": "Parda",
                        "value": "parda",
                        "order": 3
                    },
                    {
                        "label": "Preta",
                        "value": "preta",
                        "order": 4
                    },
                    {
                        "label": "Prefiro Não Responder",
                        "value": "prefiro_nao_responder",
                        "order": 5
                    }
                ],
                "validate": "required",
                "order": 14
            },
            "declaro_ppi": {
                "label": "Declaro, para os devidos fins, que sou preto, pardo ou indígena",
                "type": "radio",
                "value": [
                    {
                        "label": "Não",
                        "value": "nao",
                        "order": 0
                    },
                    {
                        "label": "Sim",
                        "value": "sim",
                        "order": 1
                    }
                ],
                "validate": "required",
                "order": 15
            },
            "portador_de_deficiencia": {
                "label": "Portador de Deficiência",
                "type": "radio",
                "value": [
                    {
                        "label": "Não",
                        "value": "nao",
                        "order": 0
                    },
                    {
                        "label": "Sim",
                        "value": "sim",
                        "order": 1
                    }
                ],
                "validate": "required",
                "order": 16
            },
            "qual_a_sua_deficiencia": {
                "label": "Qual a sua deficiência",
                "type": "text",
                "order": 17
            },
            "condicoes_prova": {
                "label": "Condições Necessárias para a Realização da Prova",
                "type": "textarea",
                "order": 18
            },
            "cep": {
                "label": "CEP",
                "type": "text",
                "validate": "required",
                "order": 19
            },
            "endereco_residencial": {
                "label": "Endereço Residencial",
                "type": "text",
                "validate": "required",
                "order": 20
            },
            "numero": {
                "label": "Número",
                "type": "text",
                "validate": "required",
                "order": 21
            },
            "complemento": {
                "label": "Complemento",
                "type": "text",
                "order": 22
            },
            "bairro": {
                "label": "Bairro",
                "type": "text",
                "validate": "required",
                "order": 23
            },
            "cidade": {
                "label": "Cidade",
                "type": "text",
                "validate": "required",
                "order": 24
            },
            "uf": {
                "label": "UF",
                "type": "select",
                "value": [
                    {
                        "label": "AC",
                        "value": "ac",
                        "order": 0
                    },
                    {
                        "label": "AL",
                        "value": "al",
                        "order": 1
                    },
                    {
                        "label": "AM",
                        "value": "am",
                        "order": 2
                    },
                    {
                        "label": "AP",
                        "value": "ap",
                        "order": 3
                    },
                    {
                        "label": "BA",
                        "value": "ba",
                        "order": 4
                    },
                    {
                        "label": "CE",
                        "value": "ce",
                        "order": 5
                    },
                    {
                        "label": "DF",
                        "value": "df",
                        "order": 6
                    },
                    {
                        "label": "ES",
                        "value": "es",
                        "order": 7
                    },
                    {
                        "label": "GO",
                        "value": "go",
                        "order": 8
                    },
                    {
                        "label": "MA",
                        "value": "ma",
                        "order": 9
                    },
                    {
                        "label": "MG",
                        "value": "mg",
                        "order": 10
                    },
                    {
                        "label": "MS",
                        "value": "ms",
                        "order": 11
                    },
                    {
                        "label": "MT",
                        "value": "mt",
                        "order": 12
                    },
                    {
                        "label": "PA",
                        "value": "pa",
                        "order": 13
                    },
                    {
                        "label": "PB",
                        "value": "pb",
                        "order": 14
                    },
                    {
                        "label": "PE",
                        "value": "pe",
                        "order": 15
                    },
                    {
                        "label": "PI",
                        "value": "pi",
                        "order": 16
                    },
                    {
                        "label": "PR",
                        "value": "pr",
                        "order": 17
                    },
                    {
                        "label": "RJ",
                        "value": "rj",
                        "order": 18
                    },
                    {
                        "label": "RN",
                        "value": "rn",
                        "order": 19
                    },
                    {
                        "label": "RO",
                        "value": "ro",
                        "order": 20
                    },
                    {
                        "label": "RR",
                        "value": "rr",
                        "order": 21
                    },
                    {
                        "label": "RS",
                        "value": "rs",
                        "order": 22
                    },
                    {
                        "label": "SC",
                        "value": "sc",
                        "order": 23
                    },
                    {
                        "label": "SE",
                        "value": "se",
                        "order": 24
                    },
                    {
                        "label": "SP",
                        "value": "sp",
                        "order": 25
                    },
                    {
                        "label": "TO",
                        "value": "to",
                        "order": 26
                    }
                ],
                "validate": "required",
                "order": 25
            },
            "celular": {
                "label": "Celular",
                "type": "text",
                "validate": "required",
                "order": 26
            },
            "e_mail": {
                "label": "E-mail",
                "type": "email",
                "validate": "required",
                "order": 27
            },
            "declaro_concordo_termos": {
                "label": "Declaro estar ciente e concordo com os <a href=\"http://143.107.146.13/pt-br/form/pos-psa-2024-2025-dout-direito#terms\">termos de inscrição no Programa de Pós-Graduação do Instituto de Psicologia da USP</a>",
                "type": "checkbox",
                "validate": "required",
                "order": 28
            },
            "declaro_revisei_inscricao": {
                "label": "Declaro que revisei todas as informações inseridas neste formulário e que elas estão corretas, e venho requerer minha inscrição como candidato(a) à vaga no Programa de Pós-Graduação em Psicologia Escolar e do Desenvolvimento Humano para o Curso de Doutorado Direto",
                "type": "checkbox",
                "validate": "required",
                "order": 29
            },
            "declaro_ciente_nao_presencial": {
                "label": "Declaro estar ciente de que o processo seletivo será realizado no formato não presencial, on-line, e que a <u>Comissão de Seleção não se responsabiliza por eventuais falhas técnicas por parte do(a) candidato(a) (tais como falta de internet, cortes de som, corte de luz, etc.) durante a realização das provas e das arguições relizadas online</u>. A sugestão é que o(a) candidato(a) se organize com antecedência para o bom andamento da prova",
                "type": "checkbox",
                "validate": "required",
                "order": 30
            }
        }',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'datahora_inicio',
        'datahora_fim',
        'boleto_valor',
        'boleto_texto',
        'boleto_data_vencimento',
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
            'name' => 'programa_id',
            'label' => 'Programa',
            'type' => 'select',
            'model' => 'Programa',
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
            'name' => 'datahora_inicio',
            'label' => 'Início',
            'type' => 'datetime',
        ],
        [
            'name' => 'datahora_fim',
            'label' => 'Fim',
            'type' => 'datetime',
        ],
        [
            'name' => 'boleto_data_vencimento',
            'label' => 'Data de Vencimento do Boleto',
            'type' => 'date',
        ],
        [
            'name' => 'boleto_valor',
            'label' => 'Valor do Boleto (R$)',
            'type' => 'number',
        ],
        [
            'name' => 'boleto_texto',
            'label' => 'Texto do Boleto',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        $fields = self::fields;
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
        $selecoes = self::get();
        $ret = [];
        foreach ($selecoes as $selecao)
            if (Gate::allows('selecoes.view'))
                $ret[$selecao->id] = $selecao->nome . ' (' . $selecao->categoria->nome . ')';
        return $ret;
    }

    /**
     * Retorna os tipos de arquivo possíveis na seleção.
     */
    public static function tiposArquivo()
    {
        return [
            [
                'nome' => 'Edital',
                'validate' => 'required'
            ],
            [
                'nome' => 'Normas para Isenção de Taxa',
                'validate' => 'required'
            ],
            [
                'nome' => 'Errata'
            ],
            [
                'nome' => 'Lista de Inscritos'
            ],
        ];
    }

    /**
     * lista de estados padrão
     */
    public static function estados()
    {
        return ['Aguardando Documentação', 'Aguardando Início', 'Em Andamento', 'Encerrada'];
    }

    /**
     * config-status
     * obtém a lista de estados formatado para select
     */
    public function getStatusToSelect()
    {
        $status = $this->config->status;
        if ($status) {
            $out = ['Em Andamento' => 'Em Andamento (sistema)'];
            foreach ($status as $item)
                foreach ($item as $key => $value)
                    if ($key == "label")
                        $out[strtolower($value)] = $value;
            return $out;
        }
    }

    /**
     * Accessor getter para $config
     */
    public function getConfigAttribute(string $value)
    {
        $value = json_decode($value);

        $out = new \StdClass;
        $out->status = $value->status ?? config('selecoes.config.status');
        return $out;
    }

    /**
     * Accessor setter para $config
     */
    public function setConfigAttribute(string|array $value)
    {
        // quando este método é invocado pelo seeder, $value vem como string JSON
        // quando este método é invocado pelo MVC, $value vem como array

        if (is_string($value)) {
            $value_decoded = json_decode($value, true); // Decodifica como array associativo
            if (is_array($value_decoded) && (json_last_error() == JSON_ERROR_NONE))
                $value = $value_decoded;    // se $value veio como string JSON, vamos utilizar $value_decoded, de modo a poder acessá-lo mais abaixo como array
        }

        $config = new \StdClass;
        $config->status = $value['status'];
        $this->attributes['config'] = json_encode($config);
    }

    /**
     * Accessor para $template
     */
    public function getTemplateAttribute(string $value)
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
        self::atualizarStatusSelecoes();
        return self::whereIn('programa_id', \Auth::user()->listarProgramasGerenciados()->pluck('id'))->get();
    }

    /**
     * Mostra lista de categorias e respectivas seleções
     * para selecionar e solicitar isenção de taxa
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarSelecoesParaSolicitacaoIsencaoTaxa()
    {
        self::atualizarStatusSelecoes();

        $categorias = Categoria::get();                                  // primeiro vamos pegar todas as seleções
        foreach ($categorias as $categoria) {                            // e depois filtrar as que não pode
            $selecoes = $categoria->selecoes;                            // primeiro vamos pegar todas as seleções
            $selecoes = $selecoes->filter(function ($selecao, $key) {    // agora vamos remover as seleções onde não se pode inscrever... a ordem de liberação é relevante!
                return ($selecao->estado != 'Encerrada');                // descarta as seleções encerradas
            });
            $categoria->selecoes = $selecoes;
        }
        return $categorias;                                              // retorna as seleções dentro de categorias
    }

    /**
     * Mostra lista de categorias e respectivas seleções
     * para selecionar e criar nova inscrição
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarSelecoesParaNovaInscricao()
    {
        self::atualizarStatusSelecoes();

        $categorias = Categoria::get();                                  // primeiro vamos pegar todas as seleções
        foreach ($categorias as $categoria) {                            // e depois filtrar as que não pode
            $selecoes = $categoria->selecoes;                            // primeiro vamos pegar todas as seleções
            $selecoes = $selecoes->filter(function ($selecao, $key) {    // agora vamos remover as seleções onde não se pode inscrever... a ordem de liberação é relevante!
                return ($selecao->estado != 'Encerrada');                // descarta as seleções encerradas
            });
            $categoria->selecoes = $selecoes;
        }
        return $categorias;                                              // retorna as seleções dentro de categorias
    }

    /**
     * Atualiza o status da seleção
     */
    public function atualizarStatus()
    {
        $tipos_arquivo_required = collect(self::tiposArquivo())->where('validate', 'required')->pluck('nome')->toArray();
        $possui_todos_os_arquivos_required = true;
        foreach ($tipos_arquivo_required as $tipo_arquivo_required)
            if (!$this->arquivos->contains('pivot.tipo', $tipo_arquivo_required)) {
                $possui_todos_os_arquivos_required = false;
                break;
            }

        $agora = Carbon::now();
        if ($this->datahora_inicio > $agora)
            $this->update(['estado' => $possui_todos_os_arquivos_required ? 'Aguardando Início' : 'Aguardando Documentação']);
        elseif ($this->datahora_inicio <= $agora && $this->datahora_fim >= $agora)
            $this->update(['estado' => $possui_todos_os_arquivos_required ? 'Em Andamento' : 'Aguardando Documentação']);
        elseif ($this->datahora_fim < $agora)
            $this->update(['estado' => 'Encerrada']);
    }

    /**
     * Atualiza os status de todas as seleções dos últimos 5 anos
     */
    public static function atualizarStatusSelecoes()
    {
        $data_limite = Carbon::today()->subYears(2);
        $selecoes = self::where('created_at', '>=', $data_limite)->get();
        foreach ($selecoes as $selecao)
            $selecao->atualizarStatus();
    }

    public function contarSolicitacoesIsencaoTaxaPorAno()
    {
        return SolicitacaoIsencaoTaxa::contarSolicitacoesIsencaoTaxaPorAno($this);
    }

    public function contarSolicitacoesIsencaoTaxaPorMes(int $ano)
    {
        return SolicitacaoIsencaoTaxa::contarSolicitacoesIsencaoTaxaPorMes($ano, $this);
    }

    public function contarInscricoesPorAno()
    {
        return Inscricao::contarInscricoesPorAno($this);
    }

    public function contarInscricoesPorMes(int $ano)
    {
        return Inscricao::contarInscricoesPorMes($ano, $this);
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
     * relacionamento com motivos de isenção de taxa
     */
    public function motivosisencaotaxa()
    {
        return $this->belongsToMany('App\Models\MotivoIsencaoTaxa', 'motivoisencaotaxa_selecao', 'selecao_id', 'motivoisencaotaxa_id')->withTimestamps();
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
