# AI Context

Atualizado em: 10/03/2026

## Objetivo do trabalho

Iniciar testes com o sistema uspdev/inscricoes-selecoes-pos (Instituto de Psicologia - IP-USP) para ser utilizado na Escola de Comunicações e Artes - ECA-USP.

## Fluxo da ECA

Tudo manual com formulários e views em Drupal.

Fluxograma: [docs/fluxo-eca.md](docs/fluxo-eca.md)

## Fluxo do IP

Atualmente neste sistema.

Fluxograma: [docs/fluxo-ip.md](docs/fluxo-ip.md)

### Programas

- Programa de Pós-Graduação em Artes Cênicas (PPGAC)
  - Maria Helena Franco de Araujo Bastos (Coordenação)
  - Paulo Gomes Staaks (Secretaria)
  - E-mail: ppgac@usp.br
  - Tel.: +55 11 3091-1608
  - [Docentes](https://www.eca.usp.br/pos/programa-de-pos-graduacao-em-artes-cenicas#corpo_docente)
  
- Programa de Pós-Graduação em Artes Visuais (PPGAV)
  - Lúcia Machado Koch (Coordenação)
  - Valesca Pinheiro Zampieri (Secretaria)
  - E-mail: ppg.av@usp.br
  - Tel.: +55 11 3091-4480
  - [Docentes](https://www.eca.usp.br/programas/artes-visuais/corpo-docente)
  
- Programa de Pós-Graduação em Ciência da Informação (PPGCI)
  - Giovana Deliberali Maimone (Coordenação)
  - Bruno Ribeiro (Secretaria)
  - E-mail: ppgci.eca@usp.br
  - Tel.: +55 11 3091-8658
  - [Docentes](https://www.eca.usp.br/pos/programa-de-pos-graduacao-em-ciencia-da-informacao#corpo_docente)
  
- Programa de Pós-Graduação em Ciências da Comunicação (PPGCOM)d
  - Maria Cristina Palma Mungioli (Coordenação)
  -	Maria das Graças Teixeira Sousa (Secretaria)
  - E-mail: ppgcom@usp.br
  - Tel.: +55 11 3091-4507
  - [Docentes](https://www.eca.usp.br/pos/ciencias-da-comunicacao#corpo_docente)
  
- Programa de Pós-Graduação em Meios e Processos Audiovisuais (PPGMPA)
  - Cecilia Antakly de Mello (Coordenação)
  - Marcia Araujo Ferreira (Secretaria)
  - E-mail: ppgmpa@usp.br
  - Tel.: +55 11 3091-4286
  - [Docentes](https://www.eca.usp.br/pos/programa-de-pos-graduacao-em-meios-e-processos-audiovisuais?current=/node/737#corpo_docente)
  
- Programa de Pós-Graduação em Música (PPGMUS)
  - Rogério Luiz Moraes Costa (Coordenação)
  - Rafael César Leite da Silva (Secretaria)
  - E-mail: ppg.musica@usp.br
  - Tel.: +55 11 3091-2948
  - [Docentes](https://www.eca.usp.br/pos/musica#corpo_docente)
  
### Aluno regular

- Anual
- Por programa
- Pagamento no ato da inscrição
- Prova e outras etapas 

### Aluno especial

- Semestral
- Por disciplina
- Pagamento quando selecionado
- Apenas a seleção

### Ponto de atenção:

- Aluno regular, na ECA tratar como inscrição. Quando matrícula, a inscrição é através da FUVEST
- Aluno especial, apenas matrícula porque a seleção de alunos especiais é fora do sistema. Na ECA, tem que adicionar no sistema a nova funcionalidade

## Diretórios válidos

~/app/uspdev/inscricoes-selecoe-pos

## O que já está pronto

- Aluno regular
- Aluno especial, apenas matrícula

## O que pode melhorar

Seeders a apartir de endpoint em [cadastros-auxiliares](https://github.com/uspdev/cadastros-auxiliares):
- Programas
- Coordenação
- Secretaria
- Contato
- Corpo docente

## Novas funcionalidades no contexto da ECA

- Seleção de alunos especiais
  - Adicionar um campo checkbox "Incluir etapa de seleção de candidatos". Quando marcado, o fluxo será:
    
    Em Elaboração -> Aguardando Início das Solicitações de Isenção de Taxa -> Período de Solicitações de Isenção de Taxa -> Aguardando Início das Inscrições -> Período de Inscrições -> **Período de Seleção de Candidatos** -> Encerrada

    ***O ideal é que o Período de Isenção de Taxa seja o mesmo que o Período de Inscrições.****
    
    ***Alunos de gradução da ECA são isentos de taxa de inscrição. Desta maneira, a lista de selecionados, deve trazer a coluna Isenção marcada e disponível para que o secretario sinalize os demais candidatos com isenção. O boleto não será gerado para os candidatos isentos.***

- Organizando o readme em tópicos
- Seeders 

## Ambiente

- ddev
- ~/app/uspdev/inscricoes-selecoes-pos
- https://laravel.ddev.site
