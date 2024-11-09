# Travel Manager

Repositório contendo o teste técnico da OnFly

## Requisitos

-   Git
-   [docker]
-   [docker-compose] (instale preferencialmente o plugin, caso já não esteja instalado)

## Build das imagens Docker

Após realizar ter clonado o repositório no seu computador, será necessário realizar as configurações iniciais do ambiente, segue os passos:

1. `docker compose pull` para baixar as imagens necessárias
2. `docker compose build --no-cache` para fazer o build das imagens
3. `docker compose up -d --wait` para fazer iniciar o container

## Configuração

Durante os passos à seguir, o comando `docker compose run api` vai ser utilizado várias vezes, para evitar a repetição, vamos abreviar o comando para `dcra`.

### Configuração do ambiente

1. Copie o arquivo `.env.example` para `.env` e edite-o conforme achar necessário
    - cp env.example .env
2. Execute o comando `dcra composer install` para instalar as dependências do projeto
3. Gere a chave de criptografia do Laravel com o comando `dcra php artisan key:generate`
4. Gere o secret do JWT com o comando `dcra php artisan jwt:secret`
5. Execute as migrations e popule o banco de dados do projeto com `dcra php artisan migrate --seed`
6. Permita que os containers tenham permissão de escrita no ambiente de desenvolvimento com o comando `dcra chown -R www-data:www-data /var/www/storage`

### Acessando o banco de dados

Esse projeto conta com um container com o [Adminer], dessa forma o acesso ao BD é bem simplificado. Para acessá-lo basta seguir as orientações:

1. Com o projeto configurado e rodando basta acessar o endereço http://localhost:9090/ para utilizar o Adminer
2. Para acessar o banco propriamente basta preencher a tela de login com os dados a seguir:
    - Sistema: MySql
    - Servidor: db
    - Usuário: sandbox
    - Senha: sandbox
    - Base de Dados: sandbox

### Executando os testes

Nesse projeto os teste automatizados foram feitos em [PHPUnit], podem ser encontrados no diretório `/tests`, segue as instruções para execução dos testes:

1. Dentro do diretório base do projeto em seu terminal, execute o seguinte comando:
    - docker compose run api vendor/bin/phpunit tests/
2. Esse comando irá executar todos os testes do projeto, mas caso queira executar um teste específico só precisa preencher o caminho relativo completo do teste

### Encerrando a execução dos containers

Para encerrar os containers, você apenas precisa rodar o comando `docker compose down --remove-orphans` dentro do terminal aberto na pasta base do projeto.

### Dicas do dev

1. Se você seguiu as instruções de configuração do ambiente e executou o `seed`, o login e senha dos dois usuários padrão são:
    - Interno 1:
        - Email: a@a.com
        - Senha: password
    - Interno 2:
        - Email: b@a.com
        - Senha: password
2. Vale ressaltar que esse projeto conta com autenticação JWT, então com exceção das rotas `/login`, `/register` e `/ping`, todas as outras rotas do projeto precisam do Token JWT.
    - Basta fazer o login ou registar um usuário e usar o valor do atributo `token` da resposta como chave autenticadora das outras rotas
3. Toda a configuração da imagem Docker que está sendo usada foi feita por mim, preferi manter bem simples e leve todo o container
4. Caso você use o [Insomnia] para executar as requisições na API, no diretório `/storage/requests/InsomniaJsons` encontrará o arquivo que pode ser importado para o app, contendo todas as rotas mapeadas

## Leitura recomendada

Para um melhor entendimento do que está acontecendo aqui, recomendo pesquisar sobre qualquer termo técnico que você desconheça, especialmente sobre `docker`, `docker compose` e `composer`.

[docker]: https://www.docker.com/
[docker-compose]: https://docs.docker.com/compose/
[Adminer]: https://www.adminer.org/
[PHPUnit]: https://docs.phpunit.de/en/11.4/
[Insomnia]: https://insomnia.rest/
