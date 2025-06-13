<?php

namespace App\Services;

use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\Parametro;
use App\Models\TipoArquivo;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Uspdev\Boleto;

class BoletoService
{
    public function gerarBoleto(Inscricao $inscricao, ?string $disciplina_sigla = null)
    {
        $extras = json_decode($inscricao->extras, true);
        $cpf = ((strtolower($extras['tipo_de_documento']) == 'passaporte') ? '99999999999' : str_replace(['-', '.'], '', $extras['cpf']));
        $parametros = Parametro::first();

        $boleto = new Boleto(config('inscricoes-selecoes-pos.ws_boleto_usuario'), config('inscricoes-selecoes-pos.ws_boleto_senha'));
        $data = array(
            'codigoUnidadeDespesa' => getenv('REPLICADO_CODUNDCLG'),
            'codigoFonteRecurso' => $parametros->boleto_codigo_fonte_recurso,
            'estruturaHierarquica' => $parametros->boleto_estrutura_hierarquica,
            'dataVencimentoBoleto' => ($inscricao->selecao->fluxocontinuo ? addWorkingDays(now(), $inscricao->selecao->boleto_offset_vencimento) : $inscricao->selecao->boleto_data_vencimento),
            'valorDocumento' => $inscricao->selecao->boleto_valor,
            'tipoSacado' => 'PF',
            'cpfCnpj' => $cpf,
            'nomeSacado' => $extras['nome'],
            'codigoEmail' => $extras['e_mail'],
            'informacoesBoletoSacado' => 'Inscrição para Seleção da Pós-Graduação - ' . $inscricao->selecao->nome . (is_null($disciplina_sigla) ? '' : ' - Disciplina ' . $disciplina_sigla),
            'instrucoesObjetoCobranca' => 'Não receber após vencimento!',
        );
        \Illuminate\Support\Facades\Log::info('$data[\'dataVencimentoBoleto\']: ' . $data['dataVencimentoBoleto']);

        try {
            config('app.debug') && Log::info('Gerando boleto para o ' . (($extras['tipo_de_documento'] == 'Passaporte') ? 'passaporte ' . $extras['numero_do_documento'] : 'CPF ' . $extras['cpf']) . '...');

            $gerar = $boleto->gerar($data);
            if ($gerar['status']) {
                $id = $gerar['value'];

                // loga situação da geração do boleto
                config('app.debug') && Log::info('$boleto->situacao(' . $id . '): ' . json_encode($boleto->situacao($id)));

                // recupera o arquivo PDF do boleto (PDF no formato binário codificado para Base64)
                $obter = $boleto->obter($id);

                // grava o boleto como um dos arquivos da inscrição, para o candidato poder acessar no site
                $arquivo_caminho = './arquivos/' . $inscricao->created_at->year . '/' . uniqid() . Str::random(27) . '.pdf';
                $arquivo_conteudo = base64_decode($obter['value']);
                Storage::put($arquivo_caminho, $arquivo_conteudo);

                // grava informações do arquivo no banco de dados
                $arquivo = new Arquivo;
                $arquivo->user_id = \Auth::user()->id;
                $arquivo->nome_original = 'boleto_' . $inscricao->id . '_' . (is_null($disciplina_sigla) ? '' : strtolower($disciplina_sigla) . '_') . Carbon::now()->format('Ymd_His') . '.pdf';
                $arquivo->caminho = $arquivo_caminho;
                $arquivo->mimeType = 'application/pdf';
                $arquivo->tipoarquivo_id = TipoArquivo::where('classe_nome', 'Inscrições')->where('nome', 'Boleto(s) de Pagamento da Inscrição')->first()->id;
                $arquivo->save();
                $arquivo->inscricoes()->attach($inscricao->id, [
                    'tipo' => 'Boleto(s) de Pagamento da Inscrição',
                    'disciplina' => $disciplina_sigla
                ]);

                if (App::environment('local') || config('inscricoes-selecoes-pos.ws_boleto_cancelar')) {

                    // cancela o boleto em ambiente de desenvolvimento, ou também em produção se ligamos a chave WS_BOLETO_CANCELAR
                    config('app.debug') && Log::info('Cancelando o boleto...');
                    $boleto->cancelar($id);

                    // loga situação da geração do boleto
                    config('app.debug') && Log::info('$boleto->situacao(' . $id . '): ' . json_encode($boleto->situacao($id)));
                }

                // retorna o conteúdo do PDF
                return ['nome' => $arquivo->nome_original, 'conteudo' => $obter['value']];
            } else {
                Log::info('Erro ao gerar boleto... $gerar[\'value\']: ' . $gerar['value']);
                return ['nome' => '', 'conteudo' => ''];
            }

        } catch (Exception $e) {
            Log::info('Erro ao gerar boleto... $e->getMessage(): ' . $e->getMessage());
            return ['nome' => '', 'conteudo' => ''];
        }
    }
}
