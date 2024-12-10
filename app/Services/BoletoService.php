<?php

namespace App\Services;

use App\Models\Parametro;
use GuzzleHttp\Client;
use UspDev\Boleto;

class BoletoService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function gerarBoleto(Selecao $selecao, $sacado)
    {
        $boleto = new Boleto(config('selecoes-pos.ws_boleto_usuario'), config('selecoes-pos.ws_boleto_senha'));
        $data = array(
            'codigoUnidadeDespesa' => 47,
            'codigoFonteRecurso' => 514,
            'estruturaHierarquica' => '\DIR\ATAC-47\SVPOSGR-47\SVPOSGR-47',
            'dataVencimentoBoleto' => $calcularDataVencimento($selecao),
            'valorDocumento' => Parametro::obterBoletoValor(),
            'tipoSacado' => 'PF',
            'cpfCnpj' => $sacado->cpf,
            'nomeSacado' => $sacado->nome,
            'codigoEmail' => $sacado->email,
            'informacoesBoletoSacado' => 'Boleto de Inscrição do Processo Seletivo da Pós-Graduação - ' . $selecao->nome,
            'instrucoesObjetoCobranca' => 'Não receber após vencimento',
        );

        $gerar = $boleto->gerar($data);
        if ($gerar['status']) {
            $id = $gerar['value'];

            // resgata informações do boleto
            \Illuminate\Support\Facades\Log::info('$boleto->situacao(' . $id . '): ' . $boleto->situacao($id));

            // recupera o arquivo PDF do boleto (PDF no formato binário codificado para Base64)
            $obter = $boleto->obter($id);

            // redireciona os dados binários do PDF para o browser
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="boleto.pdf"');
            echo base64_decode($obter['value']);

            // cancela o boleto
            $boleto->cancelar($id);
        }
    }

    private function calcularDataVencimento(Selecao $selecao)
    {
        // deve ser o 1o dia útil após o término do período de inscrições dessa seleção
    }
}
