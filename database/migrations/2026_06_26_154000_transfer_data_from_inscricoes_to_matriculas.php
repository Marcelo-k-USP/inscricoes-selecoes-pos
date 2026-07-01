<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransferDataFromInscricoesToMatriculas extends Migration
{
    public function up()
    {
        DB::transaction(function () {

            // =========================================
            // 0. BUSCA SELEÇÕES ALVO (CATEGORIA_ID = 2)
            // =========================================
            $selecoesAlvoIds = DB::table('selecoes')->where('categoria_id', 2)->pluck('id')->toArray();
            if (empty($selecoesAlvoIds))
                return;    // se não houver nenhuma seleção com essa categoria, interrompe para evitar erros

            // ====================================================
            // 1. ATUALIZAÇÃO E DUPLICAÇÃO DE TIPOS DE ARQUIVO ALVO
            // ====================================================
            $tiposAlvoIds = DB::table('tipoarquivo_categoria')->where('categoria_id', 2)->pluck('tipoarquivo_id')->toArray();
            $tipoArquivoMap = [];
            if (!empty($tiposAlvoIds)) {
                $tiposAntigos = DB::table('tiposarquivo')->whereIn('id', $tiposAlvoIds)->where('classe_nome', 'Inscrições')->get();
                foreach ($tiposAntigos as $tipoAntigo) {
                    $temOutrasCategorias = DB::table('tipoarquivo_categoria')->where('tipoarquivo_id', $tipoAntigo->id)->where('categoria_id', '!=', 2)->exists();

                    if ($temOutrasCategorias) {
                        $dadosNovoTipo = (array) $tipoAntigo;
                        unset($dadosNovoTipo['id']);
                        $dadosNovoTipo['classe_nome'] = 'Matrículas';
                        $dadosNovoTipo['created_at'] = now();
                        $dadosNovoTipo['updated_at'] = now();
                        $novoTipoId = DB::table('tiposarquivo')->insertGetId($dadosNovoTipo);
                        $tipoArquivoMap[$tipoAntigo->id] = $novoTipoId;
                        $categoriasOriginais = DB::table('tipoarquivo_categoria')->where('tipoarquivo_id', $tipoAntigo->id)->get();
                        foreach ($categoriasOriginais as $catOrig)
                            DB::table('tipoarquivo_categoria')->insert(['tipoarquivo_id' => $novoTipoId, 'categoria_id' => $catOrig->categoria_id, 'created_at' => now(), 'updated_at' => now()]);
                        $niveisOriginais = DB::table('tipoarquivo_nivelprograma')->where('tipoarquivo_id', $tipoAntigo->id)->get();
                        foreach ($niveisOriginais as $nivel)
                            DB::table('tipoarquivo_nivelprograma')->insert(['tipoarquivo_id' => $novoTipoId, 'nivelprograma_id' => $nivel->nivelprograma_id, 'created_at' => now(), 'updated_at' => now()]);
                    } else
                        DB::table('tiposarquivo')->where('id', $tipoAntigo->id)->update(['classe_nome' => 'Matrículas', 'updated_at' => now()]);
                }
            }

            // ==========================================================
            // 1.5. ATUALIZA SELECAO_TIPOARQUIVO (APENAS PARA DUPLICADOS)
            // ==========================================================
            foreach ($tipoArquivoMap as $idAntigo => $idNovo)
                DB::table('selecao_tipoarquivo')->whereIn('selecao_id', $selecoesAlvoIds)->where('tipoarquivo_id', $idAntigo)->update(['tipoarquivo_id' => $idNovo, 'updated_at' => now()]);

            // ==========================================================================
            // 1.8. VINCULA TIPOS DE ARQUIVO DA CATEGORIA 2 À CATEGORIA 1 (ALUNO REGULAR)
            // ==========================================================================
            $tiposCategoria2 = DB::table('tipoarquivo_categoria')->where('categoria_id', 2)->pluck('tipoarquivo_id')->toArray();
            foreach ($tiposCategoria2 as $tipoId) {
                $existeCat1 = DB::table('tipoarquivo_categoria')->where('tipoarquivo_id', $tipoId)->where('categoria_id', 1)->exists();
                if (!$existeCat1)
                    DB::table('tipoarquivo_categoria')->insert(['tipoarquivo_id' => $tipoId, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
            }

            // ==========================================
            // 2. MOVIMENTAÇÃO DE INSCRIÇÕES E MATRÍCULAS
            // ==========================================
            $inscricaoIds = DB::table('inscricoes')->whereIn('selecao_id', $selecoesAlvoIds)->pluck('id')->toArray();    // identifica as inscrições das seleções alvo
            if (empty($inscricaoIds))
                return;    // se não houver inscrições para mover, encerra a transação com segurança
            $inscricoes = DB::table('inscricoes')->whereIn('id', $inscricaoIds)->get();    // clona os dados para a tabela de matrículas
            foreach ($inscricoes as $inscricao) {
                $dadosMatricula = (array) $inscricao;
                $dadosMatricula['created_at'] = now();
                $dadosMatricula['updated_at'] = now();
                DB::table('matriculas')->insert($dadosMatricula);
            }

            // =======================================
            // 3. MOVIMENTAÇÃO DE VÍNCULOS DE ARQUIVOS
            // =======================================
            $arquivosVinculados = DB::table('arquivo_inscricao')->whereIn('inscricao_id', $inscricaoIds)->get();
            $arquivoIdsModificados = [];
            foreach ($arquivosVinculados as $arquivo) {
                $arquivoIdsModificados[] = $arquivo->arquivo_id;
                DB::table('arquivo_matricula')->insert(['arquivo_id' => $arquivo->arquivo_id, 'matricula_id' => $arquivo->inscricao_id, 'tipo' => $arquivo->tipo ?? '', 'disciplina' => $arquivo->disciplina ?? '', 'created_at' => now(), 'updated_at' => now()]);    // move o vínculo para a tabela pivô de matrículas
            }
            if (!empty($arquivoIdsModificados))
                foreach ($tipoArquivoMap as $idAntigo => $idNovo)
                    DB::table('arquivos')->whereIn('id', $arquivoIdsModificados)->where('tipoarquivo_id', $idAntigo)->update(['tipoarquivo_id' => $idNovo, 'updated_at' => now()]);

            // ======================================
            // 4. MOVIMENTAÇÃO DE USUÁRIOS VINCULADOS
            // ======================================
            $usuariosVinculados = DB::table('user_inscricao')->whereIn('inscricao_id', $inscricaoIds)->get();
            foreach ($usuariosVinculados as $usuario) {
                DB::table('user_matricula')->insert(['user_id' => $usuario->user_id, 'matricula_id' => $usuario->inscricao_id, 'papel' => $usuario->papel, 'created_at' => now(), 'updated_at' => now()]);
            }

            // =====================================
            // 4.5. ATUALIZAÇÃO DA TABELA PARAMETROS
            // =====================================
            DB::table('parametros')->update(['boleto_momento_envio' => 'Envio da Inscrição/Matrícula', 'updated_at' => now()]);

            // ================================
            // 5. LIMPEZA DOS REGISTROS ANTIGOS
            // ================================
            DB::table('arquivo_inscricao')->whereIn('inscricao_id', $inscricaoIds)->delete();    // remove os vínculos antigos das tabelas filhas primeiro
            // user_inscricao e inscricoes não devem ser deletados se houver outras inscrições de outras seleções alvo, mas a lógica original assume limpeza total por id
            DB::table('user_inscricao')->whereIn('inscricao_id', $inscricaoIds)->delete();
            DB::table('inscricoes')->whereIn('id', $inscricaoIds)->delete();    // remove as inscrições da tabela mãe por último
        });
    }

    public function down()
    {
        DB::transaction(function () {

            // =========================================
            // 0. BUSCA SELEÇÕES ALVO (CATEGORIA_ID = 2)
            // =========================================
            $selecoesAlvoIds = DB::table('selecoes')->where('categoria_id', 2)->pluck('id')->toArray();
            if (empty($selecoesAlvoIds))
                return;

            // ===============================================================================
            // 0.5. REMOVE VÍNCULO DA CATEGORIA 1 DOS TIPOS DA CATEGORIA 2 (ADICIONADOS NO UP)
            // ===============================================================================
            $tiposCategoria2 = DB::table('tipoarquivo_categoria')->where('categoria_id', 2)->pluck('tipoarquivo_id')->toArray();
            if (!empty($tiposCategoria2)) {
                $tiposReverterDown = DB::table('tiposarquivo')->whereIn('id', $tiposCategoria2)->where('classe_nome', 'Matrículas')->pluck('id')->toArray();
                if (!empty($tiposReverterDown))
                    DB::table('tipoarquivo_categoria')->whereIn('tipoarquivo_id', $tiposReverterDown)->where('categoria_id', 1)->delete();
            }

            // =====================================================
            // 1. REVERTE OS TIPOS DE ARQUIVO E MAPEIA OS DUPLICADOS
            // =====================================================
            $tiposAlvoIds = DB::table('tipoarquivo_categoria')->where('categoria_id', 2)->pluck('tipoarquivo_id')->toArray();
            $tipoArquivoMapDown = [];
            if (!empty($tiposAlvoIds)) {
                $tiposReverter = DB::table('tiposarquivo')->whereIn('id', $tiposAlvoIds)->where('classe_nome', 'Matrículas')->get();
                foreach ($tiposReverter as $tipoRev) {
                    $tipoOriginal = DB::table('tiposarquivo')->where('nome', $tipoRev->nome)->where('classe_nome', 'Inscrições')->first();
                    if ($tipoOriginal)
                        $tipoArquivoMapDown[$tipoRev->id] = $tipoOriginal->id;
                    else
                        DB::table('tiposarquivo')->where('id', $tipoRev->id)->update(['classe_nome' => 'Inscrições', 'updated_at' => now()]);
                }
            }

            // =========================================================
            // 1.5. REVERTE SELECAO_TIPOARQUIVO (APENAS PARA DUPLICADOS)
            // =========================================================
            foreach ($tipoArquivoMapDown as $idNovo => $idAntigo)
                DB::table('selecao_tipoarquivo')->whereIn('selecao_id', $selecoesAlvoIds)->where('tipoarquivo_id', $idNovo)->update(['tipoarquivo_id' => $idAntigo, 'updated_at' => now()]);

            // ==========================================
            // 2. MOVIMENTAÇÃO DE MATRÍCULAS E INSCRIÇÕES
            // ==========================================
            $matriculaIds = DB::table('matriculas')->whereIn('selecao_id', $selecoesAlvoIds)->pluck('id')->toArray();
            if (empty($matriculaIds))
                return;
            $matriculas = DB::table('matriculas')->whereIn('id', $matriculaIds)->get();
            foreach ($matriculas as $matricula) {
                $dadosInscricao = (array) $matricula;
                $dadosInscricao['created_at'] = now();
                $dadosInscricao['updated_at'] = now();
                DB::table('inscricoes')->insert($dadosInscricao);
            }

            // =======================================
            // 3. MOVIMENTAÇÃO DE VÍNCULOS DE ARQUIVOS
            // =======================================
            $arquivosVinculados = DB::table('arquivo_matricula')->whereIn('matricula_id', $matriculaIds)->get();
            $arquivoIdsModificados = [];
            foreach ($arquivosVinculados as $arquivo) {
                $arquivoIdsModificados[] = $arquivo->arquivo_id;
                DB::table('arquivo_inscricao')->insert(['arquivo_id' => $arquivo->arquivo_id, 'inscricao_id' => $arquivo->matricula_id, 'tipo' => $arquivo->tipo ?? '', 'disciplina' => $arquivo->disciplina ?? '', 'created_at' => now(), 'updated_at' => now()]);
            }
            if (!empty($arquivoIdsModificados))
                foreach ($tipoArquivoMapDown as $idNovo => $idAntigo)
                    DB::table('arquivos')->whereIn('id', $arquivoIdsModificados)->where('tipoarquivo_id', $idNovo)->update(['tipoarquivo_id' => $idAntigo, 'updated_at' => now()]);

            // ======================================
            // 4. MOVIMENTAÇÃO DE USUÁRIOS VINCULADOS
            // ======================================
            $usuariosVinculados = DB::table('user_matricula')->whereIn('matricula_id', $matriculaIds)->get();
            foreach ($usuariosVinculados as $usuario) {
                DB::table('user_inscricao')->insert(['user_id' => $usuario->user_id, 'inscricao_id' => $usuario->matricula_id, 'papel' => $usuario->papel, 'created_at' => now(), 'updated_at' => now()]);
            }

            // ================================
            // 4.5. REVERTE A TABELA PARAMETROS
            // ================================
            DB::table('parametros')->update(['boleto_momento_envio' => 'Envio da Inscrição', 'updated_at' => now()]);

            // =================================================
            // 5. LIMPEZA DOS REGISTROS NOVOS E TIPOS DUPLICADOS
            // =================================================
            DB::table('arquivo_matricula')->whereIn('matricula_id', $matriculaIds)->delete();
            DB::table('user_matricula')->whereIn('matricula_id', $matriculaIds)->delete();
            DB::table('matriculas')->whereIn('id', $matriculaIds)->delete();
            if (!empty($tipoArquivoMapDown)) {
                $idsParaDeletar = array_keys($tipoArquivoMapDown);
                DB::table('tipoarquivo_categoria')->whereIn('tipoarquivo_id', $idsParaDeletar)->delete();
                DB::table('tipoarquivo_nivelprograma')->whereIn('tipoarquivo_id', $idsParaDeletar)->delete();
                DB::table('tiposarquivo')->whereIn('id', $idsParaDeletar)->delete();
            }
        });
    }
}
