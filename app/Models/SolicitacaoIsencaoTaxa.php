<?php

namespace App\Models;

use App\Models\Selecao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SolicitacaoIsencaoTaxa extends Model
{
    use HasFactory;

    # solicitações de isenção de taxa não segue convenção do laravel para nomes de tabela
    protected $table = 'solicitacoesisencaotaxa';

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
            'Aguardando Comprovação', 'Isenção de Taxa Solicitada',
            'Isenção de Taxa em Avaliação', 'Isenção de Taxa Aprovada', 'Isenção de Taxa Rejeitada'
        ];
    }

    /**
     * Retorna os tipos de arquivo possíveis na solicitação de isenção de taxa.
     */
    public static function tiposArquivo()
    {
        return [
            [
                'nome' => 'Comprovação',
                'validate' => 'required'
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
     * Retorna a contagem de solicitações de isenção de taxa por ano
     *
     * Se passar $selecao a contagem é somente da seleção, se não é de todo o sistema
     *
     * @param  \App\Models\Selecao $selecao
     * @return int
     */
    public static function contarSolicitacoesIsencaoTaxaPorAno(?Selecao $selecao = null)
    {
        return self::selectRaw('year(created_at) ano, count(*) count')
            ->where('selecao_id', $selecao->id)
            ->whereYear('created_at', '>=', date('Y') - 5) // ultimos 5 anos
            ->groupBy('ano')->get();
    }

    /**
     * Retorna a contagem de solicitações de isenção de taxa por mês de determinado ano
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
    public static function contarSolicitacoesIsencaoTaxaPorMes(int $ano, ?Selecao $selecao = null)
    {
        $contagem = self::selectRaw('month(created_at) mes, count(*) count')
            ->where('selecao_id', $selecao->id)
            ->whereYear('created_at', $ano)
            ->groupBy('mes')->get();

        // vamos organizar em array por mês para facilitar a apresentação
        $ret = [];
        for ($i = 0; $i < 12; $i++) {
            $ret[] = $contagem->where('mes', $i + 1)->first()->count ?? '';
        }
        return $ret;
    }

    /**
     * Lista as solicitações de isenção de taxa autorizadas para o usuário
     *
     * Se perfiladmin mostra todas as solicitações de isenção de taxa
     * Se perfilusuario mostra as solicitações de isenção de taxa que ele está cadastrado como criador
     *
     * @return Collection
     */
    public static function listarSolicitacoesIsencaoTaxa()
    {
        if (Gate::any(['perfiladmin', 'perfilgerente']))
            return self::get();
        else
            return Auth::user()->solicitacoesisencaotaxa()->wherePivotIn('papel', ['Autor'])->get();
    }

    public static function listarSolicitacoesIsencaoTaxaPorSelecao(Selecao $selecao, int $ano)
    {
        return self::where('selecao_id', $selecao->id)->whereYear('created_at', $ano)->get();
    }

    /**
     * Verifica os arquivos da solicitação de isenção de taxa
     * Conforme for o caso, altera o estado da solicitação de isenção de taxa
     */
    public function verificarArquivos()
    {
        // obtém os tipos de arquivo requeridos
        $tipos_arquivo_requeridos = collect(self::tiposArquivo())->filter(function ($tipo) {
            return (isset($tipo['validate']) && ($tipo['validate'] == 'required'));
        });

        // obtém os tipos de arquivo da solicitação de isenção de taxa
        $arquivos_solicitacaoisencaotaxa = $this->arquivos->pluck('pivot.tipo')->countBy()->all();

        // verifica se todos os tipos requeridos estão presentes nos arquivos da solicitação de isenção de taxa
        $todos_requeridos_presentes = function() use ($tipos_arquivo_requeridos, $arquivos_solicitacaoisencaotaxa) {
            foreach ($tipos_arquivo_requeridos as $tipo_arquivo_requerido) {
                $tipo_nome = $tipo_arquivo_requerido['nome'];
                $minimo_requerido = ($tipo_arquivo_requerido['minimum_required'] ?? 1);
                if (!isset($arquivos_solicitacaoisencaotaxa[$tipo_nome]) || ($arquivos_solicitacaoisencaotaxa[$tipo_nome] < $minimo_requerido))
                    return false;
            }
            return true;
        };

        switch ($this->estado) {
            case 'Aguardando Comprovação':
                if ($todos_requeridos_presentes()) {
                    $this->estado = 'Isenção de Taxa Solicitada';    // avança o estado
                    $this->save();
                }
                break;

            case 'Realizada':
                if (!$todos_requeridos_presentes()) {
                    $this->estado = 'Aguardando Comprovação';        // retrocede o estado
                    $this->save();
                }
        }
    }

    /**
     * Mostra as pessoas que têm vínculo com a solicitação de isenção de taxa
     *
     * Se informado $pivot, retorna somente o primeiro usuário, senão retorna a lista completa
     *
     * @param  $pivot Papel da pessoa na solicitação de isenção de taxa (autor, null = todos)
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
        return $this->belongsToMany('App\Models\Arquivo', 'arquivo_solicitacaoisencaotaxa', 'solicitacaoisencaotaxa_id', 'arquivo_id')->withTimestamps();    // se eu não especificar o nome do campo como solicitacaoisencaotaxa_id, o Laravel vai pensar que é solicitacao_isencao_taxa_id, e vai dar erro
    }

    /**
     * relacionamento com users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_solicitacaoisencaotaxa', 'solicitacaoisencaotaxa_id', 'user_id')->withTimestamps();    // se eu não especificar o nome do campo como solicitacaoisencaotaxa_id, o Laravel vai pensar que é solicitacao_isencao_taxa_id, e vai dar erro
    }

    /**
     * relacionamento com seleção
     */
    public function selecao()
    {
        return $this->belongsTo(Selecao::class);
    }
}
