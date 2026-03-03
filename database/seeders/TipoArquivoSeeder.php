<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
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
                'abreviacao' => 'Edital',
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Normas para Isenção de Taxa',
                'obrigatorio' => true,
                'abreviacao' => 'NormasIsenc',
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Errata',
                'obrigatorio' => false,
                'abreviacao' => 'Errata',
            ],
            [
                'classe_nome' => 'Seleções',
                'nome' => 'Resultado',
                'obrigatorio' => false,
                'abreviacao' => 'Result',
            ],
            [
                'classe_nome' => 'Solicitações de Isenção de Taxa',
                'nome' => 'Comprovação',
                'obrigatorio' => true,
                'abreviacao' => 'Comprov',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Documento com Foto',
                'obrigatorio' => true,
                'abreviacao' => 'DocFoto',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Histórico Escolar da Graduação',
                'obrigatorio' => true,
                'abreviacao' => 'HistEscGrad',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Histórico Escolar do Mestrado',
                'obrigatorio' => true,
                'abreviacao' => 'HistEscMestr',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Diploma da Graduação',
                'obrigatorio' => true,
                'abreviacao' => 'DiplGrad',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Diploma do Mestrado',
                'obrigatorio' => true,
                'abreviacao' => 'DiplMestr',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Dissertação do Mestrado',
                'obrigatorio' => true,
                'abreviacao' => 'DissertMestr',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Proficiência em uma Língua Estrangeira',
                'obrigatorio' => true,
                'abreviacao' => 'ProficLinguaEstr',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Publicação de um Artigo em Revista Científica',
                'obrigatorio' => true,
                'abreviacao' => 'Publ1ArtRev',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica',
                'obrigatorio' => true,
                'minimo' => 2,
                'abreviacao' => 'Publ2ArtRev',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Boleto(s) de Pagamento',
                'editavel' => false,
                'obrigatorio' => false,
                'abreviacao' => 'Boleto',
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Boleto(s) de Pagamento - Disciplinas Removidas',
                'editavel' => false,
                'obrigatorio' => false,
                'abreviacao' => 'BoletoDiscRemov',
            ],
        ];

        // adiciona registros na tabela tiposarquivo
        foreach ($tiposarquivo as $tipoarquivo)
            TipoArquivo::create($tipoarquivo);

        $tipoarquivo_EDITAL = TipoArquivo::where('nome', 'Edital')->first();
        $tipoarquivo_NORMASPARAISENCAODETAXA = TipoArquivo::where('nome', 'Normas para Isenção de Taxa')->first();
        $tipoarquivo_ERRATA = TipoArquivo::where('nome', 'Errata')->first();
        $tipoarquivo_RESULTADO = TipoArquivo::where('nome', 'Resultado')->first();
        $tipoarquivo_COMPROVACAO = TipoArquivo::where('nome', 'Comprovação')->first();
        $tipoarquivo_DOCUMENTOCOMFOTO = TipoArquivo::where('nome', 'Documento com Foto')->first();
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO = TipoArquivo::where('nome', 'Histórico Escolar da Graduação')->first();
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO = TipoArquivo::where('nome', 'Histórico Escolar do Mestrado')->first();
        $tipoarquivo_DIPLOMADAGRADUACAO = TipoArquivo::where('nome', 'Diploma da Graduação')->first();
        $tipoarquivo_DIPLOMADOMESTRADO = TipoArquivo::where('nome', 'Diploma do Mestrado')->first();
        $tipoarquivo_DISSERTACAODOMESTRADO = TipoArquivo::where('nome', 'Dissertação do Mestrado')->first();
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA = TipoArquivo::where('nome', 'Comprovação de Proficiência em uma Língua Estrangeira')->first();
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de um Artigo em Revista Científica')->first();
        $tipoarquivo_COMPROVACAODEPUBLICACAODENOMINIMODOISARTIGOSEMREVISTACIENTIFICA = TipoArquivo::where('nome', 'Comprovação de Publicação de no Mínimo 2 Artigos em Revista Científica')->first();
        $tipoarquivo_BOLETOSDEPAGAMENTO = TipoArquivo::where('nome', 'Boleto(s) de Pagamento')->first();
        $tipoarquivo_BOLETOSDEPAGAMENTODISCIPLINASREMOVIDAS = TipoArquivo::where ('nome', 'Boleto(s) de Pagamento - Disciplinas Removidas')->first();

        // adiciona registros na tabela tipoarquivo_nivelprograma
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
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_MESTRADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_MESTRADOPSICOLOGIASOCIALPST);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADOPSICOLOGIASOCIALPST);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETONEUROCIENCIASECOMPORTAMENTONEC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIACLINICAPSC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIAEXPERIMENTALPSE);
        $tipoarquivo_BOLETOSDEPAGAMENTO->niveisprogramas()->attach($nivelprograma_id_DOUTORADODIRETOPSICOLOGIASOCIALPST);

        // adiciona registros na tabela selecao_tipoarquivo
        $selecao_id_SELECAO2025ALUNOREGULARNEC = Selecao::where('nome', 'Seleção 2025 Aluno Regular NEC')->first()->id;
        $selecao_id_SELECAO2025ALUNOESPECIAL = Selecao::where('nome', 'Seleção 2025 Aluno Especial')->first()->id;
        $tipoarquivo_EDITAL->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_EDITAL->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_NORMASPARAISENCAODETAXA->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_NORMASPARAISENCAODETAXA->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_ERRATA->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_ERRATA->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_RESULTADO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_RESULTADO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_COMPROVACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_COMPROVACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_DOCUMENTOCOMFOTO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_DOCUMENTOCOMFOTO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_DIPLOMADAGRADUACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_DIPLOMADAGRADUACAO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_DIPLOMADOMESTRADO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_DISSERTACAODOMESTRADO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->selecoes()->attach($selecao_id_SELECAO2025ALUNOREGULARNEC);
        $tipoarquivo_BOLETOSDEPAGAMENTO->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
        $tipoarquivo_BOLETOSDEPAGAMENTODISCIPLINASREMOVIDAS->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);

        // adiciona registros na tabela tipoarquivo_categoria
        $categoria_id_ALUNOREGULAR = Categoria::where('nome', 'Aluno Regular')->first()->id;
        $categoria_id_ALUNOESPECIAL = Categoria::where('nome', 'Aluno Especial')->first()->id;
        $tipoarquivo_DOCUMENTOCOMFOTO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_HISTORICOESCOLARDAGRADUACAO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_HISTORICOESCOLARDOMESTRADO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_DIPLOMADAGRADUACAO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_DIPLOMADAGRADUACAO->categorias()->attach($categoria_id_ALUNOESPECIAL);
        $tipoarquivo_DIPLOMADOMESTRADO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_DISSERTACAODOMESTRADO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_COMPROVACAODEPROFICIENCIAEMUMALINGUAESTRANGEIRA->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_COMPROVACAODEPUBLICACAODEUMARTIGOEMREVISTACIENTIFICA->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_BOLETOSDEPAGAMENTO->categorias()->attach($categoria_id_ALUNOREGULAR);
        $tipoarquivo_BOLETOSDEPAGAMENTO->categorias()->attach($categoria_id_ALUNOESPECIAL);
        $tipoarquivo_BOLETOSDEPAGAMENTODISCIPLINASREMOVIDAS->categorias()->attach($categoria_id_ALUNOESPECIAL);
    }
}
