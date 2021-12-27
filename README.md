# Teste Promobit

O projeto em questão, trata-se de um teste com o intuito de criar um CRUD de produtos, tags e extração de relatório de relevância de produtos.

# Tecnologias Utilizadas

1. Wampserver;
2. PHP 7;
3. MySQL 5.7;
4. Bootstrap;

# Orientações

1. Clone este repositório;
2. Acesse o localhost > phpmyadmin;
3. Crie um Banco de Dados chamado teste-promobit;

Insira o seguinte código ->
create database teste-promobit;

use teste-promobit;

CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
);

CREATE TABLE `tag` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
);

CREATE TABLE `product_tag` (
   `product_id` int NOT NULL,
   `tag_id` int NOT NULL,
   PRIMARY KEY (`product_id`,`tag_id`),
   CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
   CONSTRAINT `tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
);

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
);


# SQL de extração de Relatório de Relevancia de produtos
SELECT ta.name, ta.id, COUNT(tp.product_id) as qtd_produto
from tag ta 
LEFT JOIN product_tag tp on ta.id = tp.tag_id WHERE ta.name LIKE ? GROUP by 1;
