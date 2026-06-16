<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reorderings extends Migration
{
    public function up()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            $table->string('disciplina')->nullable()->after('tipo')->change();
        });

        Schema::table('parametros', function (Blueprint $table) {
            $table->string('link_acompanhamento_especiais')->nullable()->after('boleto_estrutura_hierarquica')->change();
            $table->integer('max_disciplinas_aluno_especial')->nullable()->after('link_acompanhamento_especiais')->change();
            $table->string('email_servicoposgraduacao')->after('max_disciplinas_aluno_especial')->change();
            $table->string('email_secaoinformatica')->nullable()->after('email_servicoposgraduacao')->change();
            $table->string('email_gerenciamentosite')->nullable()->after('email_secaoinformatica')->change();
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->string('nome', 100)->after('id')->change();
            $table->string('sigla')->after('nome')->change();
            $table->string('descricao', 255)->nullable()->after('sigla')->change();
            $table->boolean('matricula')->default(false)->after('descricao')->change();
            $table->unsignedBigInteger('parametro_id')->nullable()->after('matricula')->change();
            $table->string('email_secretaria')->nullable()->after('parametro_id')->change();
            $table->string('link_acompanhamento')->nullable()->after('email_secretaria')->change();
        });

        Schema::table('selecoes', function (Blueprint $table) {
            $table->integer('ingresso_semestre')->after('id')->change();
            $table->integer('ingresso_ano')->after('ingresso_semestre')->change();
            $table->string('nome', 100)->after('ingresso_ano')->change();
            $table->string('descricao', 255)->nullable()->after('nome')->change();
            $table->string('estado', 90)->after('descricao')->change();
            $table->unsignedBigInteger('categoria_id')->after('estado')->change();
            $table->unsignedBigInteger('programa_id')->nullable()->after('categoria_id')->change();
            $table->boolean('tem_taxa')->default(true)->after('programa_id')->change();
            $table->boolean('fluxo_continuo')->default(false)->after('tem_taxa')->change();
            $table->datetime('solicitacoesisencaotaxa_datahora_inicio')->nullable()->after('fluxo_continuo')->change();
            $table->datetime('solicitacoesisencaotaxa_datahora_fim')->nullable()->after('solicitacoesisencaotaxa_datahora_inicio')->change();
            $table->datetime('inscricoes_datahora_inicio')->after('solicitacoesisencaotaxa_datahora_fim')->change();
            $table->datetime('inscricoes_datahora_fim')->after('inscricoes_datahora_inicio')->change();
            $table->date('boleto_data_vencimento')->nullable()->after('inscricoes_datahora_fim')->change();
            $table->integer('boleto_offset_vencimento')->nullable()->after('boleto_data_vencimento')->change();
            $table->decimal('boleto_valor', 8, 2)->nullable()->after('boleto_offset_vencimento')->change();
            $table->string('boleto_texto')->nullable()->after('boleto_valor')->change();
            $table->string('email_inscricaoaprovacao_texto')->nullable()->after('boleto_texto')->change();
            $table->string('email_inscricaorejeicao_texto')->nullable()->after('email_inscricaoaprovacao_texto')->change();
            $table->json('template')->nullable()->after('email_inscricaorejeicao_texto')->change();
            $table->json('settings')->nullable()->after('template')->change();
        });

        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->string('abreviacao')->after('nome')->change();
        });
    }

    public function down()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            $table->string('disciplina')->nullable()->after('updated_at')->change();
        });

        Schema::table('parametros', function (Blueprint $table) {
            $table->string('email_servicoposgraduacao')->after('updated_at')->change();
            $table->string('email_secaoinformatica')->nullable()->after('email_servicoposgraduacao')->change();
            $table->string('link_acompanhamento_especiais')->nullable()->after('email_secaoinformatica')->change();
            $table->string('email_gerenciamentosite')->nullable()->after('link_acompanhamento_especiais')->change();
            $table->integer('max_disciplinas_aluno_especial')->nullable()->after('email_gerenciamentosite')->change();
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->unsignedBigInteger('parametro_id')->nullable()->after('id')->change();
            $table->string('nome', 100)->after('parametro_id')->change();
            $table->string('sigla')->after('nome')->change();
            $table->string('descricao', 255)->nullable()->after('sigla')->change();
            $table->string('email_secretaria')->nullable()->after('updated_at')->change();
            $table->string('link_acompanhamento')->nullable()->after('email_secretaria')->change();
            $table->boolean('matricula')->default(false)->after('link_acompanhamento')->change();
        });

        Schema::table('selecoes', function (Blueprint $table) {
            $table->string('nome', 100)->after('id')->change();
            $table->string('estado', 90)->after('nome')->change();
            $table->string('descricao', 255)->nullable()->after('estado')->change();
            $table->boolean('tem_taxa')->default(true)->after('descricao')->change();
            $table->datetime('solicitacoesisencaotaxa_datahora_inicio')->nullable()->after('tem_taxa')->change();
            $table->datetime('solicitacoesisencaotaxa_datahora_fim')->nullable()->after('solicitacoesisencaotaxa_datahora_inicio')->change();
            $table->datetime('inscricoes_datahora_inicio')->after('solicitacoesisencaotaxa_datahora_fim')->change();
            $table->datetime('inscricoes_datahora_fim')->after('inscricoes_datahora_inicio')->change();
            $table->decimal('boleto_valor', 8, 2)->nullable()->after('inscricoes_datahora_fim')->change();
            $table->string('boleto_texto')->nullable()->after('boleto_valor')->change();
            $table->date('boleto_data_vencimento')->nullable()->after('boleto_texto')->change();
            $table->string('email_inscricaoaprovacao_texto')->nullable()->after('boleto_data_vencimento')->change();
            $table->string('email_inscricaorejeicao_texto')->nullable()->after('email_inscricaoaprovacao_texto')->change();
            $table->json('template')->nullable()->after('email_inscricaorejeicao_texto')->change();
            $table->json('settings')->nullable()->after('template')->change();
            $table->unsignedBigInteger('categoria_id')->after('settings')->change();
            $table->unsignedBigInteger('programa_id')->nullable()->after('categoria_id')->change();
            $table->boolean('fluxo_continuo')->default(false)->after('updated_at')->change();
            $table->integer('boleto_offset_vencimento')->nullable()->after('fluxo_continuo')->change();
            $table->integer('ingresso_semestre')->after('boleto_offset_vencimento')->change();
            $table->integer('ingresso_ano')->after('ingresso_semestre')->change();
        });

        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->string('abreviacao')->after('updated_at')->change();
        });
    }
}
