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
                'nome' => 'Comprovação de Proficiência em Língua Estrangeira',
                'obrigatorio' => true,
            ],
            [
                'classe_nome' => 'Inscrições',
                'nome' => 'Histórico Escolar e Diploma de Gradução',
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
        foreach (TipoArquivo::where('classe_nome', 'Inscrições')->get() as $tipoarquivo) {
            $tipoarquivo->niveis()->attach($nivel_id_MESTRADO);
            $tipoarquivo->niveis()->attach($nivel_id_DOUTORADO);
            $tipoarquivo->niveis()->attach($nivel_id_DOUTORADODIRETO);
        }
    }
}
