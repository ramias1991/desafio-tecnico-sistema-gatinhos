# Instruções
1. Fazer o clone do projeto através do link
git clone https://ramiassouza@bitbucket.org/ramiassouza/teste-inovadora-ramias.git

2. Abrir o caminho do projeto no terminal e rodar o comando abaixo e aguardar a instalação do container:
> docker-compose up -d --build

3. Verificar o Gateway dos containers:

#### Nginx
> docker inspect id_nginx 
  
4. Copiar o gateway e alterar no arquivo '.env' na linha 55 a variável LOCAL_IP
 
#### Redis
> docker inspect id_redis

5. Copiar o gateway e alterar no arquivo 'src/.env' na linha 26
> REDIS_HOST=novo_gateway

6. Inserir as tabelas do banco através do migration
> docker-compose run artisan migrate

7. Insira um novo usuário teste
> docker-compose run artisan db:seed --class=UserSeeder
- email: teste@gmail.com
- senha: 123456
 
8. Acessar o projeto através do localhost:7000

9. Para executar os testes criados, acesse o bash do container php e execute o comando
> php artisan test
