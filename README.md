# Seleções Pós (Laravel 11)

Permite ...
    
## Instalação

* Inicio

```bash
    sudo apt-get install php-soap
```

Descomentar a linha extension=soap do php.ini    

```bash
    git clone git@github.com:uspdev/selecoes-pos selecoes-pos
    cd selecoes-pos
    composer update
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
```

* Crie seu repositório remoto e faça as seguintes configurações

```bash
    git remote remove origin
    git remote add origin git@github.com:uspdev/selecoes-pos
    git push -u origin main
```

### Testando envio de e-mails utilizando a plataforma Mailtrap

Utilizando a plataforma [Mailtrap](https://mailtrap.io/) é possível capturar os e-mails enviados sem que estes cheguem à caixa de entrada dos destinatários, possibilitando assim testar e analisar o envio de e-mails antes de se colocar em produção.

__Como Utilizar__
    
Após criar e entrar com uma conta na plataforma, é possível gerar as credenciais para o envio de e-mail no sistema utilizado, no caso do Laravel as credenciais seriam semelhantes à figura a seguir:

![image](https://user-images.githubusercontent.com/47902146/206538191-1b75750d-819b-4bc6-a8cf-efd7b8bf993b.png)

Assim, basta substituir tais credenciais no `.env` do projeto e enviar os e-mails normalmente que estes serão capturados na caixa de entrada do Mailtrap, sem serem enviados aos seus destinatários.

## Histórico

* ??/??/????
    - Versão 1.

## Funcionalidades

* ...

## Requisitos

...

## Atualização

...

### Em produção

Para receber as últimas atualizações do sistema rode:

```sh
git pull
composer install --no-dev
php artisan migrate
```

## Instalação

...

### Básico

```sh
git clone git@github.com:uspdev/selecoes-pos
composer install
cp .env.example .env
php artisan key:generate
```

Configure o .env conforme a necessidade

### Cache (opcional)

Algumas partes podem usar cache ([https://github.com/uspdev/cache](https://github.com/uspdev/cache)). Para utilizá-lo você precisa instalar e configurar o memcached no mesmo servidor da aplicação.

```bash
apt install memcached
vim /etc/memcached.conf
    I = 5M
    -m 128

/etc/init.d/memcached restart
```

### Email

O gmail utiliza senhas de app (https://support.google.com/accounts/answer/185833?hl=pt-BR) desde maio/2022. Siga os passos para gerar uma senha de app para sua aplicação.

### Apache ou nginx

Deve apontar para a <pasta do projeto>/public, assim como qualquer projeto laravel.

No Apache é possivel utilizar a extensão MPM-ITK (http://mpm-itk.sesse.net/) que permite rodar seu Servidor Virtual com usuário próprio. Isso facilita rodar o sistema como um usuário comum e não precisa ajustar as permissões da pasta storage/.

```bash
sudo apt install libapache2-mpm-itk
sudo a2enmod mpm_itk
sudo service apache2 restart
```

Dentro do seu virtualhost coloque

```apache
<IfModule mpm_itk_module>
AssignUserId nome_do_usuario nome_do_grupo
</IfModule>
```

### Senha única

Cadastre uma nova URL no configurador de senha única utilizando o caminho https://seu_app/callback. Guarde o callback_id para colocar no arquivo .env.

### Banco de dados

* DEV

    `php artisan migrate:fresh --seed`

* Produção

    `php artisan migrate`

### Supervisor (opcional)

Para as filas de envio de email o sistema precisa de um gerenciador que mantenha rodando o processo que monitora as filas. O recomendado é o **Supervisor**. No Ubuntu ou Debian instale com:

    sudo apt install supervisor

Modelo de arquivo de configuração. Como **`root`**, crie o arquivo `/etc/supervisor/conf.d/selecoes_pos_queue_worker_default.conf` com o conteúdo abaixo:

    [program:selecoes_pos_queue_worker_default]
    command=/usr/bin/php /home/sistemas/selecoes-pos/artisan queue:listen --queue=default --tries=3 --timeout=60
    process_num=1
    username=www-data
    numprocs=1
    process_name=%(process_num)s
    priority=999
    autostart=true
    autorestart=unexpected
    startretries=3
    stopsignal=QUIT
    stderr_logfile=/var/log/supervisor/selecoes_pos_queue_worker_default.log

Ajustes necessários:

    command=<ajuste o caminho da aplicação>
    username=<nome do usuário do processo do selecoes-pos>
    stderr_logfile = <aplicacao>/storage/logs/<seu arquivo de log>

Reinicie o **Supervisor**

    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all

## Problemas e soluções

Alguma dica de como resolver problemas comuns?

## Histórico

Registre o log das principais alterações
