# Desafio técnico - Sistema de gatinhos
### Projeto criado como desafio técnico para a empresa Inovadora sistemas
1. O sistema foi feito num ambiente Docker e com o Framework Laravel
2. O sistema realiza a busca de gatinhos listados na api ![TheCatsApi]{https://thecatapi.com/}
3. Para acessar o sistema, deverá ser feito um login, realizando o cadastro previamente.

### Obs1.: Deverá ter o Docker instalado.

## Instruções
1. Abrir o caminho do projeto no terminal e rode o comando abaixo para instalar todas as dependências do projeto.
> docker-compose run composer install

2. Rodar o comando abaixo e aguardar a instalação do container:
> docker-compose up -d --build

3. Verificar o Gateway do container Nginx:

#### Nginx
> docker inspect id_nginx 
  
4. Copiar o gateway e alterar no arquivo '.env' na linha 55 a variável LOCAL_IP
 
5. Inserir as tabelas do banco através do migration
> docker-compose run artisan migrate

### O próximo passo é para a criação de um usuário teste na base
6. Insira um novo usuário teste
> docker-compose run artisan db:seed --class=UserSeeder
- email: teste@gmail.com
- senha: 123456
 
7. Acessar o projeto através do localhost:7000

### O próximo passo é para os testes criados.

8. Para executar os testes criados, acesse o bash do container php e execute o comando
> php artisan test

### Obs2.: Há também as opções de Api com os seguintes Endpoints
| Método | URI                                           | Body                    | Description                                            |
|--------|-----------------------------------------------|-------------------------|--------------------------------------------------------|
| GET    | http://localhost:7000/api/cats                | Key JWT - Bearen token  | Obtém a lista de gatinhos cadastrados localmente.      |
| POST   | http://localhost:7000/api/cats                | search-cat              | Realiza a busca dos gatinhos e adiciona na base local. |
| PUT    | http://localhost:7000/api/edit-cat            | id_cat name description | Edita o gatinho passando os parâmetros.                |
| DELETE | http://localhost:7000/api/delete-cat/{id_cat} |                         | Deleta um gatinho informando o id na URI.              |
| DELETE | http://localhost:7000/api/delete-all          |                         | Deleta toda a lista de gatinhos da base local.         |
| POST   | http://localhost:7000/api/login               | email password          | Realiza o login no sistema para obter a chave JWT.     |

