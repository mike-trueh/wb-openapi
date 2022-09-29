<?php

namespace WbOpenApi\Endpoints;

use InvalidArgumentException;
use WbOpenApi\Client\OpenApiClientInterface;

class Content implements ApiInterface
{
    /**
     * @var OpenApiClientInterface
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function __construct(OpenApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Список номенклатуры
     *
     * @param int $limit Максимальное кол-во карточек, которое необходимо вывести. (Максимальное значение - 1000, по умолчанию 10)
     * @param int $offset Смещение от начала списка с указанными критериями поиска и сортировки.
     * @param string $searchValue Значение для поиска. Всегда строка. Поиск работает по номенклатурам, артикулам и баркодам.
     * @param string $sortColumn Выбор параметра для сортировки. По умолчанию updateAt desc - дата обновления по убыванию. Доступен так же параметр сортировки - hasPhoto.
     * @param bool $asc Направление сортировки. True - по возрастанию, false - по убыванию.
     * @return array|null
     */
    public function cardsList(int $limit = 10, int $offset = 0, string $searchValue = '', string $sortColumn = 'updateAt', bool $asc = false): ?array
    {
        return $this->client->post('content/v1/cards/list', [
            'sort' => [
                'limit' => $limit,
                'offset' => $offset,
                'searchValue' => $searchValue,
                'sortColumn' => $sortColumn,
                'ascending' => $asc,
            ]
        ]);
    }

    /**
     * Список несозданных номенклатур с ошибками
     *
     * **ВАЖНО**: Для того чтобы убрать номенклатур из ошибочных, надо повторно сделать запрос с исправленными ошибками на создание карточки товара.
     *
     * @return array|null
     */
    public function cardsErrorList(): ?array
    {
        return $this->client->get('content/v1/cards/error/list');
    }

    /**
     * Получение карточек товара по вендор кодам (артикулам)
     *
     * Метод позволяет получить полную информацию по карточке товара с помощью вендор кода(-ов) номенклатуры из карточки товара (артикулов).
     *
     * @param string[] $vendorCodes Массив идентификаторов НМ поставщика. Максимальное количество в запросе 100.
     * @return array|null
     */
    public function cardsFilter(array $vendorCodes): ?array
    {
        if (count($vendorCodes) > 100) {
            throw new InvalidArgumentException('No more than 100 nomenclatures can be loaded at a time');
        }

        return $this->client->post('content/v1/cards/filter', [
            'vendorCodes' => $vendorCodes
        ]);
    }
}