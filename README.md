# 🙈 Test Back-end

API RESTful

**Framework:** Laravel

## Configurando o laravel

1. composer install

## 📀 Instalação do Docker

[Download do Docker](https://www.docker.com/products/docker-desktop)

Por não ter disponível o OS Linux, fiz a configuração com o Windows

### Comandos necessários

1. docker-compose up
2. docker exec -it test-php bash
3. cd app
4. php artisan config:cache
5. php artisan migrate
6. php artisan db:seed --class=UserCategorySeeder

#### Opcionais 

- docker-compose up -d  [Executando o container em detached mode usando a flag -d, continua rodando em segundo plano, liberando o seu terminal]

- docker-compose up --build [Executado quando houver alterações em seu arquivo dockerfile ou docker-compose]

## 🚧 Erros comuns

### Caso executar docker-compose up --build e houver as seguintes pastas:
Dentro da pasta .docker, excluir as pastas logs e data 

## 📗 Documentação da API - Swagger UI

[Link Documentação](http://localhost:8000/api/docs)

### Comando para atualizar a documentação
1. docker exec -it test-php bash
2. cd app
3. php artisan l5-swagger:generate


## 💾 SGBD - phpMyAdmin

[Link para acessar](http://localhost:8080)

- Servidor: mysqldb
- Utilizador: root
- Palavra-passe: root

## Comando para executar testes com PHPUnit
- vendor/bin/phpunit tests