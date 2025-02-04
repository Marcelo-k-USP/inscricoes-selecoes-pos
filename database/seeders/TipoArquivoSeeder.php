<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\Selecao;
use App\Models\TipoArquivo;

class TipoArquivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiposarquivo = [
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Edital',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Normas para Isenção de Taxa',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Errata',
                'obrigatorio' => false,
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Lista de Inscritos',
                'obrigatorio' => false,
            ],
            [
                'classe_nome' => 'Solicitações de Isenção de Taxa',
                'nome' => 'Comprovação',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Documento com Foto',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Histórico Escolar da Graduação',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Histórico Escolar do Mestrado',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Diploma da Graduação',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Diploma do Mestrado',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Dissertação do Mestrado',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Proficiência em uma Língua Estrangeira',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Publicação de um Artigo em Revista Científica',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica',
                'obrigatorio' => true,
                'minimo' => 2,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Boleto(s) de Pagamento da Inscrição',
                'editavel' => false,
                'obrigatorio' => false,
            ],
        ];

        // adiciona registros na tabela tiposarquivo
        foreach ($tiposarquivo as $tipoarquivo)
            TipoArquivo::create($tipoarquivo);

        // adiciona registros na tabela selecao_tipoarquivo
        $selecao_id_SELECAO2025ALUNOREGULAR = Selecao::where('nome', 'Seleção 2025 Aluno Regular')->first()->id;
        $selecao_id_SELECAO2025ALUNOESPECIAL = Selecao::where('nome', 'Seleção 2025 Aluno Especial')->first()->id;
        foreach (TipoArquivo::all() as $tipoarquivo) {
            $tipoarquivo->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULAR);
            $tipoarquivo->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        }

        // adiciona registros na tabela tipoarquivo_nivel
        $nivel_id_MESTRADO = Nivel::where('nome', 'Mestrado')->first()->id;
        $nivel_id_DOUTORADO = Nivel::where('nome', 'Doutorado')->first()->id;
        $nivel_id_DOUTORADODIRETO = Nivel::where('nome', 'Doutorado Direto')->first()->id;
        $tipoarquivo_DOCUMENTOCOMFOTO = TipoArquivo::where('nome', 'Documento com Foto')->first();
        $tipoarquivo_DOCUMENTOCOMFOTO->niveis()->attach($nivel_id_MESTRADO);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO = TipoArquivo::where('nome', 'Histórico Escolar da Graduação')->first();
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveis()->attach($nivel_id_MESTRADO);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO = TipoArquivo::where('nome', 'Histórico Escolar do Mestrado')->first();
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_DIPLOMADAGRADUACAO = TipoArquivo::where('nome', 'Diploma da Graduação')->first();
        $tipoarquivo_DIPLOMADAGRADUACAO->niveis()->attach($nivel_id_MESTRADO);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_DIPLOMADOMESTRADO = TipoArquivo::where('nome', 'Diploma do Mestrado')->first();
        $tipoarquivo_DIPLOMADOMESTRADO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_DIPLOMADOMESTRADO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_DISSERTACAODOMESTRADO = TipoArquivo::where('nome', 'Dissertação do Mestrado')->first();
        $tipoarquivo_DISSERTACAODOMESTRADO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA = TipoArquivo::where('nome', 'Comprovação de Proficiência em uma Língua Estrangeira')->first();
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveis()->attach($nivel_id_MESTRADO);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de um Artigo em Revista Científica')->first();
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveis()->attach($nivel_id_DOUTORADODIRETO);
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica')->first();
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO = TipoArquivo::where('nome', 'Boleto(s) de Pagamento da Inscrição')->first();
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveis()->attach($nivel_id_MESTRADO);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveis()->attach($nivel_id_DOUTORADO);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveis()->attach($nivel_id_DOUTORADODIRETO);
    }
}
