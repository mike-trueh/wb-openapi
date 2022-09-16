# Wildberries OpenApi 1.1

Wildberries OpenApi - PHP SDK пакет для взаимодействия с
API Продавца v1.1 [Wildberries](https://openapi.wb.ru)

## Установка

``` bash
$ composer require mike-trueh/wb-openapi
```

## Поддерживаемые методы

_Сделаны только несколько методов из официальной документации_

- prices
    - info
    - prices
- content
    - cards list
    - cards error list
    - cards filter

[Официальная документация](https://openapi.wb.ru)

## Примеры

**incomes**

``` php
$client = new WbOpenApi\Client\Curl('API_KEY');
$prices = new WbOpenApi\Endpoints\Prices($client);

var_dump($prices->info(1));
```

## Тестирование

Запуск тестов

``` bash
$ composer test
```