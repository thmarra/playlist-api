# Playlist API

Aplicação para trackear o acompanhamento de séries e videos de forma geral.

## Tecnologias

O projeto foi feito com as seguintes tecnologias e é preciso que elas estejam disponíveis na máquina para seu 
correto funcionamento:

* PHP 8.0
* Composer 2
* MySQL / SQLite

## Rodando o projeto
* Instalar as tecnologias do projeto
* `php -S 127.0.0.1:8000`

## Tarefas
- [x] Montagem do esqueleto do projeto
  * Composer: 
    * `composer init`
    * Inserir o autoload no index.php
  * PSR-4
  * Helper da resposta em json
  * Arquivo de rotas e health check
- [x] Integração com o banco de dados
* Env: `composer require symfony/dotenv`
- [ ] CRUD do Catálogo de Videos

## Banco
```
CREATE TABLE `catalog` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `status` enum('PENDING','IN_PROGRESS','DONE') NOT NULL DEFAULT 'PENDING',
  `plataform` varchar(255) NULL,
  `url` text NULL
); 
```