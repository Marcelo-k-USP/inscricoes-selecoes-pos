<?php

namespace App\Services;

use App\Models\Feriado;
use App\Models\Inscricao;
use App\Models\Parametro;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Uspdev\Boleto;

class BoletoService
{
    public function gerarBoleto(Inscricao $inscricao)
    {
        $extras = json_decode($inscricao->extras, true);
        $cpf = (($extras['tipo_de_documento'] == 'Passaporte') ? '99999999999' : str_replace(['-', '.'], '', $extras['cpf']));

        $boleto = new Boleto(config('selecoes-pos.ws_boleto_usuario'), config('selecoes-pos.ws_boleto_senha'));
        $data = array(
            'codigoUnidadeDespesa' => 47,
            'codigoFonteRecurso' => 514,
            'estruturaHierarquica' => '\DIR\ATAC-47\SVPOSGR-47\SVPOSGR-47',
            'dataVencimentoBoleto' => formatarData(Feriado::adicionarDiasUteis($inscricao->selecao->data_fim, 1)),    // a data de vencimento do boleto deve ser o primeiro dia útil passado o período de inscrições da seleção em questão
            'valorDocumento' => Parametro::obterBoletoValor(),
            'tipoSacado' => 'PF',
            'cpfCnpj' => $cpf,
            'nomeSacado' => $extras['nome'],
            'codigoEmail' => $extras['e_mail'],
            'informacoesBoletoSacado' => 'Boleto de Inscrição do Processo Seletivo da Pós-Graduação - ' . $inscricao->selecao->nome,
            'instrucoesObjetoCobranca' => 'Não receber apos vencimento!',
        );

        try {
            Log::info('$data: ' . json_encode($data));/////////////////////////////////////////////////////////////////////////////////////
            Log::info('Gerando boleto para o ' . (($extras['tipo_de_documento'] == 'Passaporte') ? 'passaporte ' . $extras['numero_do_documento'] : 'CPF ' . $extras['cpf']) . '...');

            $gerar = $boleto->gerar($data);
            if ($gerar['status']) {
                $id = $gerar['value'];

                // loga situação da geração do boleto
                Log::info('$boleto->situacao(' . $id . '): ' . $boleto->situacao($id));

                // recupera o arquivo PDF do boleto (PDF no formato binário codificado para Base64)
                $obter = $boleto->obter($id);

                // cancela o boleto em ambiente de desenvolvimento, ou também em produção se ligamos a chave WS_BOLETO_CANCELAR
                if (App::environment('local') || config('selecoes-pos.ws_boleto_cancelar')) {
                    Log::info('Cancelando o boleto...');

                    $boleto->cancelar($id);

                    // loga situação da geração do boleto
                    Log::info('$boleto->situacao(' . $id . '): ' . $boleto->situacao($id));
                }

                // retorna o conteúdo do PDF
                return base64_decode($obter['value']);
            } else {
                Log::info('$gerar[\'value\']: ' . $gerar['value']);
                return '123';
            }

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
