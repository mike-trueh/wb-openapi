# Wildberries OpenApi 1.4

Wildberries OpenApi - PHP SDK пакет для взаимодействия с
API Продавца v1.4 [Wildberries](https://openapi.wb.ru)

## Установка

``` bash
$ composer require mike-trueh/wb-openapi
```

## Поддерживаемые методы

_Сделаны только несколько методов из официальной документации_

- Prices
    - info
    - prices
- Content
    - ~~cards list~~ Устаревший метод, вместо него нужно использовать cardsCursorList
    - cards cursor list
    - cards error list
    - cards filter
    - cards update
- Supply
    - get supplies
        - обертки для метода get supplies:
            - get active supplies
            - get delivery supplies
    - new supply
- Statistics. **Клиент - CurlStats** [см. пример](#примеры-работы-с-методами-статистики)
    - incomes
    - stocks
    - orders
    - sales
    - reportDetailByPeriod
    - exciseGoods

[Официальная документация](https://openapi.wb.ru)

## Примеры

**Prices. Получение информации о ценах**

``` php
$client = new \WbOpenApi\Client\Curl('API_KEY');
$prices = new \WbOpenApi\Endpoints\Prices($client);

var_dump($prices->info(1));
```

### Примеры работы с методами статистики

**Incomes. Поставки**

``` php
// STATISTICS_TOKEN Используется отдельный токен статистики
$client = new \WbOpenApi\Client\CurlStats('STATISTICS_TOKEN');
$supplier = new \WbOpenApi\Endpoints\Statistics($client);

var_dump($supplier->incomes(new DateTime()));
```

## Тестирование

Запуск тестов

``` bash
$ composer test
```