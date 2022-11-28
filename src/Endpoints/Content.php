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
     * **Устаревший метод.** Список номенклатуры
     *
     * @param int $limit Максимальное кол-во карточек, которое необходимо вывести. (Максимальное значение - 1000, по умолчанию 10)
     * @param int $offset Смещение от начала списка с указанными критериями поиска и сортировки.
     * @param string $searchValue Значение для поиска. Всегда строка. Поиск работает по номенклатурам, артикулам и баркодам.
     * @param string $sortColumn Выбор параметра для сортировки. По умолчанию updateAt desc - дата обновления по убыванию. Доступен так же параметр сортировки - hasPhoto.
     * @param bool $asc Направление сортировки. True - по возрастанию, false - по убыванию.
     * @return array|null
     * @deprecated Вместо этого метода нужно использовать cardsCursorList
     */
    public function cardsList(int $limit = 10, int $offset = 0, string $searchValue = '', string $sortColumn = 'updateAt', bool $asc = false): ?array
    {
        return $this->client->post('content/v1/cards/list', ['sort' => ['limit' => $limit, 'offset' => $offset, 'searchValue' => $searchValue, 'sortColumn' => $sortColumn, 'ascending' => $asc]]);
    }

    /**
     * Метод позволяет получить список созданых НМ по фильтру (баркод, вендор код, номер номенклатуры) с пагинацией и сортировкой.
     *
     * @link https://openapi.wildberries.ru/#tag/Kontent-Prosmotr/paths/~1content~1v1~1cards~1cursor~1list/post
     * @param int $limit Кол-во запрашиваемых КТ.
     * @param string|null $updatedAt Время обновления последней КТ из предыдущего ответа на запрос списка КТ.
     * @param int|null $nmID Номенклатура последней КТ из предыдущего ответа на запрос списка КТ.
     * @param string|null $textSearch Поиск по номеру НМ или артикулу товара.
     * @param int $withPhoto -1 - Показать все КТ. 0 - Показать КТ без фото. 1 - Показать КТ с фото.
     * @param string|null $sortColumn Поле по которому будет сортироваться список КТ (пока что поддерживается только updatedAt).
     * @param bool $asc Тип сортировки.
     * @return array
     */
    public function cardsCursorList(int $limit = 100, string $updatedAt = null, int $nmID = null, string $textSearch = null, int $withPhoto = -1, string $sortColumn = null, bool $asc = false): array
    {
        if (!in_array($withPhoto, [-1, 0, 1])) {
            throw new InvalidArgumentException('WithPhoto must be -1, 0 or 1');
        }

        return $this->client->post('content/v1/cards/cursor/list', [
            'sort' => [
                'cursor' => [
                    'updatedAt' => $updatedAt,
                    'nmID' => $nmID,
                    'limit' => $limit
                ],
                'filter' => [
                    'textSearch' => $textSearch,
                    'withPhoto' => $withPhoto,
                ],
                'sort' => [
                    'sortColumn' => $sortColumn,
                    'ascending' => $asc
                ]
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

        return $this->client->post('content/v1/cards/filter', ['vendorCodes' => $vendorCodes]);
    }

    /**
     * Редактирование КТ
     *
     * Метод позволяет отредактировать несколько карточек за раз. Редактирование КТ происходит асинхронно, после отправки запрос становится в очередь на обработку.
     *
     * **ВАЖНО**: Баркоды (skus) не подлежат удалению или замене. Попытка заменить существующий баркод приведет к добавлению нового баркода к существующему. Номенклатуры, содержащие ошибки, не обновляются и попадают в раздел "Список несозданных НМ с ошибками" с описанием допущенной ошибки. Для того чтобы убрать НМ из ошибочных, необходимо повторно сделать запрос с исправленными ошибками.
     *
     * @param array $cards
     * @return array|null
     */
    public function cardsUpdate(array $cards): ?array
    {
        return $this->client->post('content/v1/cards/update', $cards);
    }
}