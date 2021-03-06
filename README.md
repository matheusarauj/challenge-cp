# Capyba Backend - Desafio técnico backend 

Para contemplar os requisitos desse desafio, foi desenvolvido uma API para um sistema de cadastro de currículos DEVS
, onde é possível criar e procurar candidatos.

## 1 - Framework

Para esse desafio foi escolhido o framework PHP [Lumen](https://lumen.laravel.com/docs), um micro-framework do [Laravel](https://laravel.com/docs/contributions).

O laravel é um framework super completo, porém o lumen é uma versão mais enxuta, trazendo apenas as principais features
necessárias para o desenvolvimento de uma API, visando isso, o escolhi para solucionar esse desafio.

## 2 - Features

1 - Principais:

- Endpoint para cadastro de usuário **OK**
- Endpoint de termos de uso e políticas de privacidade **OK**
- Endpoint Login **OK**
- Endpoint Logout **OK**
- Endpoint com listagem de itens públicos **OK**
- Endpoint para listagem de itens restritos **OK**
- Endpoint para editar perfil **OK**
- Endpoint para confirmar token recebido por email **OK**
- Endpoint para reenviar token para email **OK**
- Testes **OK**
- SWAGGER **OK**
- README **OK**


## 2 - Bônus

- Deploy 
- Acesso admin 	
- Endpoint para alterar senha com confirmação da senha atual **OK**
- Cadastro via API do google 
- Seed **OK**

## 3 - Regra de negócio

Ao criar uma conta no sistema, será enviado um email para o email inserido, com um código, esse código deve ser confirmado.
( Mesmo com a conta criada mas o email ainda não tenha sido confirmado, o sistema deve bloquear acesso do usuário nas
rotas restritas ), caso o email não chegue, ou tenha o desejo de gerar um novo código, o sistema também disponibiliza 
essa funcionalidade. É possível que o e-mail caia na caixa de spam.

É possível procurar, cadastrar, editar ou até mesmo excluir currículos de uma base de dados.
Cada currículo tem seu nome, descrição, site, e-mail, telefone, nível do profissional, escolaridade entre
outros atributos. 

Cada currículo também tem uma ou mais experiências. Cada experiência tem sua empresa, local, data de início e fim
e a descrição da função.

Ainda na área restrita o usuário consegue alterar a senha de sua conta, precisando confirmar sua senha atual,
e também editar dados da sua conta como, nome, email e foto.

É possivel realizar logout, que desvalida o token do usuario.

## 4 - Estrutura do projeto

| Diretório | Responsabilidade |
| --------  | ---------------- |
| **/database** |  *Migrations e Seeders do projeto* |
| **/routes**   |  *Declaração dos endpoints* |
| **/tests**    |  *Testes do sistema* |
| **/app/Exception** | *Exceções customizadas* |
| **/app/Http/Controllers** | *Controllers da aplicação* |
| **/app/Http/Middleware**  | *Middlewares da aplicação* |
| **/app/Models** | *Modelos dos objetos da aplicação* |
| **/app/Repositories** | *Repositórios que comunicam diretamente com database*
| **/app/Services** | *Serviços responsáveis por gerir a regra de negócio da aplicação* |

## 5 - Setup

### 1 - Instalar o PHP 7.4 e o composer

Como não tenho conhecimento em qual SO irá ser execultado, deixarei o link para documentação oficial.

[Instalar PHP](https://www.php.net/manual/pt_BR/install.php)

[Instalar COMPOSER](https://getcomposer.org/doc/00-intro.md)

### 2 - Baixar o projeto para maquina
Pode ser feito o download do ZIP pelo proprio github, ou pelo terminal via linha de comando.
```

### 3 - Instalar dependencias

```shell
composer install
```

### 4 - Configurar variáveis de ambiente

Existe um arquivo na raiz do projeto chamado `.env.example`, basta renomeá-lo para apenas `.env` ou criar uma cópia dele.

Algumas variáveis estão sem valores, esses valores foram enviados em um DOC junto com a entrega do desafio,
é necessário substituir essas variáveis, pelas que estão nesse arquivo recebido via email, para que funcione tudo como deveria
(upload de imagem, envio de email e conexão com banco de dados).

### 5 - Migrations e Seeders

Primeiramente rodar migrations


```shell
php artisan migrate
```

Existem 4 Seeders no projeto, que são:

- Seed de estados (Obrigatória), para popular a tabela de estados do sistema.
- Seed de cidades (Obrigatória), para popular a tabela de cidades do sistema.

Para roda-las,  use os seguintes comandos

```shell
php artisan db:seed --class=StateSeeder

php artisan db:seed --class=CitySeeder
```

Existem outras duas seeds no projeto:

- Seed de Usuários (Opcional), para popular a usuários com algumas contas por default.
- Seed de Curriculos e Experiências (Opcional), para popular as tabelas de currículos e experiências com cadastros aleatórios ( importante que essas seeds podem ser
  executada N vezes, porém antes de executá-la, a seed de usuários tem que ter sido executada pelo menos 1 vez)


```shell
php artisan db:seed --class=UserSeeder

php artisan db:seed --class=ResumeSeeder

php artisan db:seed --class=ExperienceSeeder
```

## 6 - Rodar o projeto

Nessa etapa iremos rodar o projeto, e rodar os tests do sistema.

para inicializar o projeto digite:

```shell
 php -S localhost:8000 public/index.php   
```

com o projeto rodando, podemos visualizar documentação do **swagger** no seguinte link:

```shell
http://localhost:8000/api/documentation
```

agora podemos rodar os testes para validar que o setup foi bem sucedido, execute o comando:

```shell
./vendor/bin/phpunit 
```


