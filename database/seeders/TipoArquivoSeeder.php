<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\NivelPrograma;
use App\Models\Programa;
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
        $selecao_id_SELECAO2025ALUNOREGULAR= Selecao::where('nome', 'Seleção 2025 Aluno Regular')->first()->id;
        $selecao_id_SELECAO2025ALUNOESPECIAL= Selecao::where('nome', 'Seleção 2025 Aluno Especial')->first()->id;
        foreach (TipoArquivo::all() as $tipoarquivo) {
            $tipoarquivo->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULAR);
            $tipoarquivo->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        }

        // adiciona registros na tabela tipoarquivo_nivelprograma
        $tipoarquivo_DOCUMENTOCOMFOTO = TipoArquivo::where('nome', 'Documento com Foto')->first();
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO = TipoArquivo::where('nome', 'Histórico Escolar da Graduação')->first();
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO = TipoArquivo::where('nome', 'Histórico Escolar do Mestrado')->first();
        $tipoarquivo_DIPLOMADAGRADUACAO = TipoArquivo::where('nome', 'Diploma da Graduação')->first();
        $tipoarquivo_DIPLOMADOMESTRADO = TipoArquivo::where('nome', 'Diploma do Mestrado')->first();
        $tipoarquivo_DISSERTACAODOMESTRADO = TipoArquivo::where('nome', 'Dissertação do Mestrado')->first();
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA = TipoArquivo::where('nome', 'Comprovação de Proficiência em uma Língua Estrangeira')->first();
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de um Artigo em Revista Científica')->first();
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica')->first();
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO = TipoArquivo::where('nome', 'Boleto(s) de Pagamento da Inscrição')->first();
        $nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Neurociências e Comportamento (NEC)'); })->first()->id;
        $nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Escolar e do Desenvolvimento Humano (PSA)'); })->first()->id;
        $nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Clínica (PSC)'); })->first()->id;
        $nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Experimental (PSE)'); })->first()->id;
        $nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Social (PST)'); })->first()->id;
        $nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Neurociências e Comportamento (NEC)'); })->first()->id;
        $nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Escolar e do Desenvolvimento Humano (PSA)'); })->first()->id;
        $nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Clínica (PSC)'); })->first()->id;
        $nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Experimental (PSE)'); })->first()->id;
        $nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Social (PST)'); })->first()->id;
        $nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado Direto'); })->whereHas('programa', function ($query) { $query->where('nome', 'Neurociências e Comportamento (NEC)'); })->first()->id;
        $nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado Direto'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Escolar e do Desenvolvimento Humano (PSA)'); })->first()->id;
        $nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado Direto'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Clínica (PSC)'); })->first()->id;
        $nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado Direto'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Experimental (PSE)'); })->first()->id;
        $nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST = NivelPrograma::whereHas('nivel', function ($query) { $query->where('nome', 'Doutorado Direto'); })->whereHas('programa', function ($query) { $query->where('nome', 'Psicologia Social (PST)'); })->first()->id;
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DOCUMENTOCOMFOTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DIPLOMADAGRADUACAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DIPLOMADOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_DISSERTACAODOMESTRADO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTODAINSCRICAO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);
    }
}
