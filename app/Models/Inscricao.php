<?php

namespace App\Models;

use App\Models\Selecao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Inscricao extends Model
{
    use HasFactory;

    # inscrições não segue convenção do laravel para nomes de tabela
    protected $table = 'inscricoes';

    protected $fillable = [
        'selecao_id',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'selecao_id',
            'label' => 'Seleção',
            'type' => 'hidden',
            'model' => 'Selecao',
            'data' => [],
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
     * lista de estados padrão
     */
    public static function estados()
    {
        return [
            'Aguardando Documentação', 'Realizada',
            'Em Avaliação', 'Aprovada', 'Rejeitada'
        ];
    }

    /**
     * Retorna os tipos de arquivo possíveis na inscrição.
     */
    public static function tiposArquivo()
    {
        return [
            [
                'nome' => 'Documento com Foto',
                'validate' => 'required'
            ],
            [
                'nome' => 'Comprovação de Proficiência em Língua Estrangeira',
                'validate' => 'required'
            ],
            [
                'nome' => 'Histórico Escolar e Diploma de Gradução',
                'validate' => 'required'
            ],
            [
                'nome' => 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica',
                'validate' => 'required',
                'minimum_required' => 2
            ],
            [
                'nome' => 'Boleto de Pagamento da Inscrição',
                'editable' => 'none'
            ]
        ];
    }

    /**
     * Valores possiveis para pivot do relacionamento com users
     */
    #
    public static function pessoaPapeis($formSelect = false)
    {
        if ($formSelect)
            return ['Autor' => 'Autor'];
        else
            return ['Autor'];
    }

    /**
     * Retorna a contagem de inscrições por ano
     *
     * Se passar $selecao a contagem é somente da seleção, se não é de todo o sistema
     *
     * @param  \App\Models\Selecao $selecao
     * @return int
     */
    public static function contarInscricoesPorAno(?Selecao $selecao = null)
    {
        return self::selectRaw('year(created_at) ano, count(*) count')
            ->where('selecao_id', $selecao->id)
            ->whereYear('created_at', '>=', date('Y') - 5) // ultimos 5 anos
            ->groupBy('ano')->get();
    }

    /**
     * Retorna a contagem de inscrições por mês de determinado ano
     *
     * Se passar $selecao a contagem é somente da seleção, se não é de todo o sistema
     *
     * Retorno em array sendo o 1o elemento correspondente à contagem de janeiro,
     * o segundo elemento é a contagem de fevereiro, e assim por diante.
     * o array de retorno, portanto, possui 12 elementos
     *
     * @param  int $ano
     * @param  \App\Models\Selecao $selecao
     * @return array
     */
    public static function contarInscricoesPorMes(int $ano, ?Selecao $selecao = null)
    {
        $contagem = self::selectRaw('month(created_at) mes, count(*) count')
            ->where('selecao_id', $selecao->id)
            ->whereYear('created_at', $ano)
            ->groupBy('mes')->get();

        // vamos organizar em array por mês para facilitar a apresentação
        $ret = [];
        for ($i = 0; $i < 12; $i++)
            $ret[] = $contagem->where('mes', $i + 1)->first()->count ?? '';
        return $ret;
    }

    /**
     * Lista as inscrições autorizadas para o usuário
     *
     * Se perfiladmin mostra todas as inscrições
     * Se perfilusuario mostra as inscrições que ele está cadastrado como criador
     *
     * @return Collection
     */
    public static function listarInscricoes()
    {
        if (Gate::any(['perfiladmin', 'perfilgerente']))
            return self::get();
        else
            return Auth::user()->inscricoes()->wherePivotIn('papel', ['Autor'])->get();
    }

    public static function listarInscricoesPorSelecao(Selecao $selecao, int $ano)
    {
        return self::where('selecao_id', $selecao->id)->whereYear('created_at', $ano)->get();
    }

    /**
     * Verifica os arquivos da inscrição
     * Conforme for o caso, altera o estado da inscrição
     */
    public function verificarArquivos()
    {
        // obtém os tipos de arquivo requeridos
        $tipos_arquivo_requeridos = collect(self::tiposArquivo())->filter(function ($tipo) {
            return (isset($tipo['validate']) && ($tipo['validate'] == 'required'));
        });

        // obtém os tipos de arquivo da inscrição
        $arquivos_inscricao = $this->arquivos->pluck('pivot.tipo')->countBy()->all();

        // verifica se todos os tipos requeridos estão presentes nos arquivos da inscrição
        $todos_requeridos_presentes = function() use ($tipos_arquivo_requeridos, $arquivos_inscricao) {
            foreach ($tipos_arquivo_requeridos as $tipo_arquivo_requerido) {
                $tipo_nome = $tipo_arquivo_requerido['nome'];
                $minimo_requerido = ($tipo_arquivo_requerido['minimum_required'] ?? 1);
                if (!isset($arquivos_inscricao[$tipo_nome]) || ($arquivos_inscricao[$tipo_nome] < $minimo_requerido))
                    return false;
            }
            return true;
        };

        switch ($this->estado) {
            case 'Aguardando Documentação':
                if ($todos_requeridos_presentes()) {
                    $this->estado = 'Realizada';                  // avança o estado
                    $this->save();
                }
                break;

            case 'Realizada':
                if (!$todos_requeridos_presentes()) {
                    $this->estado = 'Aguardando Documentação';    // retrocede o estado
                    $this->save();
                }
        }
    }

    /**
     * Mostra as pessoas que têm vínculo com a inscrição
     *
     * Se informado $pivot, retorna somente o primeiro usuário, senão retorna a lista completa
     *
     * @param  $pivot Papel da pessoa na inscrição (autor, null = todos)
     * @return App\Models\User|Collection
     */
    public function pessoas($pivot = null)
    {
        if ($pivot)
            return $this->users()->wherePivot('papel', $pivot)->first();
        else
            return $this->users()->withPivot('papel');
    }

    /**
     * relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->belongsToMany('App\Models\Arquivo', 'arquivo_inscricao')->withPivot('tipo')->withTimestamps();
    }

    /**
     * relacionamento com users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_inscricao')->withTimestamps();
    }

    /**
     * relacionamento com seleção
     */
    public function selecao()
    {
        return $this->belongsTo(Selecao::class);
    }
}
