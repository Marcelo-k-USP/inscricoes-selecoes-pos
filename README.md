# Sobre o projeto

Trata o fluxo de inscrições para processos seletivos da pós-graduação.
Não trata do fluxo que segue após isso: avaliação, inscrição na pós-graduação, etc.

# Características

Permite que candidatos solicitem isenção de taxa de inscrição e realizem inscrição.
Ambas as "entidades" (isenção de taxa de solicitação e inscrição) possuem fluxo de estados.
Para que um candidato solicite isenção de taxa ou se inscreva, ele precisa estar logado.
Usuários externos à USP devem realizar um cadastro local antes dessas ações.
Os usuários locais são gravados também na tabela users, embora possuam modelo próprio no projeto.

Os gerentes devem cadastrar as seleções nas quais os candidatos se inscreverão/solicitarão isenção de taxa.
Cada seleção tem um formulário próprio, gerado a partir de um template, e editável pelo gerente (excetos por campos utilizados pelo sistema, que não podem ser removidos, como CPF, e-mail, etc.).
O estado da seleção é modificado quando o gerente altera a data início/fim e também quando as seleções são consultadas (neste momento, o sistema verifica se alguma seleção passou da data início/fim, e muda o estado de acordo).
O estado também é modificado quando o gerente sobe/remove os documentos da seleção (edital, etc.), pois não podemos abrir uma seleção sem que ela tenha esses documentos.
Ao cadastrar uma nova seleção, o gerente deve informar a quais linhas de pesquisa/temas ela está atrelada (se a categoria da seleção for aluno regular, pois na categoria de aluno especial não temos linhas de pesquisa/temas, o aluno especial se inscreve para disciplina(s)).
Ao cadastrar uma nova seleção, todos motivos de isenção de taxa são automaticamente associados à ela; cabe ao gerente verificar se é isso mesmo o desejado para a nova seleção.
Ao cadastrar uma nova seleção na categoria aluno especial, todas as disciplinas são automaticamente associados à ela; cabe ao ferente verificar se é isso mesmo o desejado para a nova seleção.

Os gerentes são atrelados aos programas.
Cada gerente pode acessar seleções, solicitações de taxa de inscrição e inscrições somente de seus programas associados.
Há quatro funções para gerentes: secretários(as) dos programas, coordenadores dos programas, serviço de pós-graduação e coordenadores de pós-graduação.
Gerentes com função serviço de pós-graduação e coordenadores de pós-graduação podem acessar todas as seleções, solicitações de taxa de inscrição e inscrições.

Há duas categorias de processos seletivos: aluno regular e aluno especial.
No caso de aluno regular, as seleções/inscrições/solicitações de isenção de taxa dizem respeito a um programa específico.
O aluno regular, ao se inscrever, deve especificar a linha de pesquisa/tema no qual está se inscrevendo.
No caso de aluno especial, as seleções/inscrições/solicitações de isenção de taxa não são atreladas a um programa.
O aluno especial, ao se inscrever, deve especificar a(s) disciplina(s) na(s) qual(is) está se inscrevendo.

Cada linha de pesquisa/tema possui uma relação de orientadores, restritos a professores da unidade.
As linhas de pesquisa/temas são relacionadas aos níveis da pós-graduação (mestrado, doutorado, doutorado direto).
Se um aluno regular se inscreve, por exemplo, para o nível de mestrado, só lhe serão permitidas as linhas de pesquisa/temas desse programa dessa seleção que estejam relacionadas ao nível escolhido.

Cada seleção contém informativos (edital, etc.), que são documentos que o candidato pode consultar.
Além disso, em cada seleção o gerente também define quais documentos o candidato pode (ou deve) subir quando da solicitação de isenção de taxa e quando da inscrição.
O tipo de documento de boletos não é removível nem renomeável. O candidato não sobe documento desse tipo, pois ele é gerado quando do envio da inscrição.
Inscrições para programas podem ser de três níveis diferentes (Mestrado, Doutorado ou Doutorado Direto) e os tipos de documentos dessas inscrições podem variar conforme o nível e o programa. Tipos de documentos com diferenciação por níveis e programas é algo que só faz sentido nas inscrições.

Para completar a inscrição, o candidato deve clicar em Enviar.
Então é gerado um boleto e enviado por e-mail para o candidato pagar a taxa de inscrição.
No caso de aluno regular, é gerado um único boleto.
No caso de aluno especial, é gerado um boleto para cada disciplina na qual ele se inscreveu, e enviado para o candidato um único e-mail com todos esses boletos anexados.
Algumas informações necessárias para a geração de boletos se encontra na tabela parametros, que é editável pelos admins em tela.

E-mails são disparados quando do envio de solicitações de isenção de taxa e inscrições, bem como de mudança de seus estados por parte dos gerentes (por exemplo, colocando em análise, aprovando, ou rejeitando).
Para verificar todos os envios de e-mail que o sistema realiza, basta verificar o método update dos controllers de solicitação de isenção de taxa e de inscrição. Além disso, há também os envios de e-mail para controle de usuário (esqueceu sua senha e cadastro de novo usuário externo).

## Changelog

Veja o [histórico de atualizações](docs/changelog.md).

## Requisitos

Este sistema foi projetado para rodar em servidores linux (Ubuntu e Debian).

-   Laravel 11
-   PHP 8.3
-   Apache ou Nginx
-   Banco de dados local (MariaDB mas pode ser qualquer um suportado pelo Laravel)
-   Git
-   Composer
-   Credenciais para senha única
-   Acesso ao replicado (visão Pessoa - VUps, Estrutura - VUes e Financeiro - VUfi)

Bibliotecas necessárias do php:

    apt install php-sybase php-mysql php-xml php-intl php-mbstring php-gd php-curl php-zip php-soap

Descomentar a linha extension=soap do php.ini    

## Atualização

Caso você já tenha instalado o sistema e aplique uma nova atualização, sempre deve rodar:

    composer install --no-dev
    php artisan migrate

Também deve observar no [changelog](docs/changelog.md) se tem alguma outra coisa a ser ajustada, por exemplo o arquivo .env

## Instalação

    cd /var/www/html
    git clone git@github.com:USPdev/inscricoes-selecoes-pos
    cd inscricoes-selecoes-pos
    composer install
    cp .env.example .env
    php artisan key:generate

Criar user e banco de dados (em mysql):

    sudo mysql
    create database inscricoes-selecoes-pos;
    create user 'inscricoes-selecoes-pos'@'%' identified by '<<password here>>';
    grant all privileges on inscricoes-selecoes-pos.* to 'inscricoes-selecoes-pos'@'%';
    flush privileges;

#### ################################ ####
## Configuração em ambiente de produção ##
#### ################################ ####

### Configurar o cache

A biblioteca (https://github.com/uspdev/cache) usada no replicado utiliza o servidor memcached. Se você pretende utilizá-lo instale e configure ele:

    sudo apt install memcached
    sudo vim /etc/memcached.conf
        I = 5M
        -m 128

    /etc/init.d/memcached restart

### E-mail

Configurar a conta de e-mail para acesso menos seguro pois a conexão é via smtp.

### Configurar o apache ou nginx

Criar novo arquivo inscricoes-selecoes-pos.conf em /etc/apache2/sites-available; nele, dentro da tag VirtualHost, o DocumentRoot deve apontar para /var/www/html/inscricoes-selecoes-pos/public. E para que as rotas funcionem, adicionar, ainda dentro dessa tag, a seguinte configuração:

    <Directory /var/www/html/inscricoes-selecoes-pos/public>
        AllowOverride All
    </Directory>

E, em seguida, executar:

    sudo a2enmod rewrite
    sudo service apache2 restart

No Apache é possivel utilizar a extensão MPM-ITK (http://mpm-itk.sesse.net/) que permite rodar seu _Servidor Virtual_ com usuário próprio. Isso facilita rodar o sistema como um usuário comum e não precisa ajustar as permissões da pasta `storage/`.

    sudo apt install libapache2-mpm-itk
    sudo a2enmod mpm_itk                        # habilita o módulo
    sudo service apache2 restart

Dentro do inscricoes-selecoes-pos.conf, dentro da tag VirtualHost coloque:

    <IfModule mpm_itk_module>
        AssignUserId nome_do_usuario nome_do_grupo
    </IfModule>

### Configurar senha única

Cadastre uma nova URL no configurador de senha única utilizando o caminho `https://seu_app/callback`. Guarde o callback_id para colocar no arquivo `.env`.

### Edite o arquivo .env

Há várias opções que precisam ser ajustadas nesse arquivo. Faça com atenção para não deixar passar nada. O arquivo está todo documentado.

### Popular banco de dados

    php artisan migrate

Os setores e respectivos designados podem ser importados do Replicado. Para isso rode:

    php artisan db:seed --class=SetorReplicadoSeeder

Depois de importado faça uma conferência para não haver inconsistências.

### Instalar e configurar o Supervisor

Para as filas de envio de e-mail, o sistema precisa de um gerenciador que mantenha rodando o processo que monitora as filas. O recomendado é o **Supervisor**. No Ubuntu ou Debian instale com:

    sudo apt install supervisor

Modelo de arquivo de configuração. Como **`root`**, crie o arquivo `/etc/supervisor/conf.d/inscricoes_selecoes_pos_queue_worker_default.conf` com o conteúdo abaixo:

    [program:inscricoes_selecoes_pos_queue_worker_default]
    command=/usr/bin/php /var/www/html/inscricoes-selecoes-pos/artisan queue:listen --queue=default --tries=3 --timeout=60
    process_num=1
    username=www-data
    numprocs=1
    process_name=%(process_num)s
    priority=999
    autostart=true
    autorestart=unexpected
    startretries=3
    stopsignal=QUIT
    stderr_logfile=/var/www/html/chamados/storage/logs/inscricoes_selecoes_pos_queue_worker_default.log

Ajustes necessários:

    command=<ajuste o caminho da aplicação>
    username=<nome do usuário do processo do chamados>
    stderr_logfile = <aplicacao>/storage/logs/<seu arquivo de log>

Reinicie o **Supervisor**

    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all

### Permissão de escrita na pasta 'storage' ao usuário do browser:

É necessária essa permissão, pois o site utiliza sessões, que são gravadas em storage/framework/sessions.
E se ligarmos o modo debug, o site também quer gravar em storage/logs.

    sudo chown -R www-data:www-data /var/www/html/inscricoes-selecoes-pos/storage
    sudo chmod -R 755               /var/www/html/inscricoes-selecoes-pos/storage
    sudo service apache2 restart

#### ################### ####
## Atualização em produção ##
#### ################### ####

Para receber as últimas atualizações do sistema rode:

    cd /var/www/html/inscricoes-selecoes-pos
    git pull
    composer install --no-dev
    php artisan migrate

Para atualizar os pacotes utilizados pelo sistema (por exemplo, o laravel-usp-theme), rode:

    composer update

Caso tenha alguma atualização, não deixe de conferir o readme.md quanto a outras providências que podem ser necessárias.

#### ####################################### ####
## Configuração em ambiente de desenvolvimento ##
#### ####################################### ####

Ainda é preciso descrever melhor mas pode seguir as instruções para ambiente de produção com os ajustes necessários.

    php artisan migrate:fresh --seed

O senhaunica-fake pode não ser adequado pois o sistema coloca as pessoas nos respectivos setores com as informações da senha única.

Para subir o servidor

    php artisan serve

**CUIDADO**: você pode enviar e-mails indesejados para as pessoas.

Para enviar e-mails é necessário executar as tarefas na fila. Para isso, em outro terminal rode

    php artisan queue:listen

## Problemas e soluções

Ao rodar pela primeira vez com apache, as variáveis de ambiente relacionadas ao replicado não ficam disponíveis. Nesse caso é necessário restartar o apache.

https://www.php.net/manual/pt_BR/function.getenv.php#117301

Para limpar e recriar todo o DB, rode sempre que necessário:

    php artisan migrate:fresh --seed

## Histórico

-   ??/??/????
    -   versão 1.0

## Detalhamento técnico

Foram utilizados vários recursos do laravel que podem não ser muito trivial para todos.

-   O monitoramento de novos chamados ou novas mensagens nos chamados é feito usando _observers_ (https://laravel.com/docs/8.x/eloquent#observers)
-   Os e-mails enviados são colocados em filas (jobs) para liberar a execução do php (https://laravel.com/docs/8.x/mail#queueing-mail)

-   O sistema faz uso dos seguintes serviços externos: WSBoleto da USP, Recaptcha v2 do Google e Viacep (que é gratuito, diferente do webservice dos Correios, que exige convênio específico).

-   Quase a totalidade da implementação deste sistema foi inspirado no chamados; muito código foi copiado de lá, e adaptado: as solicitações de isenção de taxa e inscrições deste sistema são de certa forma similares aos chamados do sistema de chamados, as seleções deste sistema são de certa forma similares às filas do sistema de chamados, e os programas deste sistema são de certa forma similares aos setores do sistema de chamados.

-   A tela de funções foi inspirada no datagrad, embora a implementação tenha sido nova.

-   O gerenciamento de usuários locais por admins foi inspirado no impressoras.

-   Como este sistema utiliza Laravel 11, alguns comandos tiveram que ser reescritos (em relação ao sistema de chamados em Laravel 8). A biblioteca laravelcollective\html foi deprecada, e passamos a utilizar a biblioteca spatie\laravel-html. Com isso, por exemplo, a antiga sintaxe que era assim:
    {!! Form::open(['url' => 'chamados']) !!}
passou a ser assim:
    {{ html()->form('post', 'inscricoes')->open() }}

-   Em sua versão inicial, os seeders contêm dados específicos para o IP-USP. Para migrar para outras unidades, pode-se desconsiderar esses seeders, ou modificá-los com os dados da unidade em questão (seeders funcoes, programas, linhas de pesquisa/temas, disciplinas, etc.).

