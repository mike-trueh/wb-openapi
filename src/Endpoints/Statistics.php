<?php

namespace WbOpenApi\Endpoints;

use DateTime;
use InvalidArgumentException;
use WbOpenApi\Client\OpenApiClientInterface;

class Statistics implements ApiInterface
{
    /**
     * @var OpenApiClientInterface
     */
    private $client;

    /**
     * **Внимание!** Для работы этих методов нужно использовать клиент \WbOpenApi\Client\CurlStats из-за отдельного домена для статистики
     *
     * @param OpenApiClientInterface $client
     */
    public function __construct(OpenApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Поставки
     *
     * @param DateTime $dateFrom
     * @return array|null
     */
    public function incomes(DateTime $dateFrom): ?array
    {
        return $this->client->get('api/v1/supplier/incomes', ['dateFrom' => $dateFrom]);
    }

    /**
     * Склад.
     *
     * Данные обновляются раз в сутки. Сервис статистики не хранит историю остатков товаров, поэтому получить данные об остатках товаров на прошедшую, не сегодняшнюю, дату - невозможно.
     *
     * @param DateTime $dateFrom Начальная дата периода
     * @return array|null
     */
    public function stocks(DateTime $dateFrom): ?array
    {
        return $this->client->get('api/v1/supplier/stocks', ['dateFrom' => $dateFrom]);
    }

    /**
     * Заказы.
     *
     * **Важно:** гарантируется хранение данных по заказам не более 90 дней от даты заказа. Данные обновляются раз в 30 минут. Точное время обновления информации в сервисе можно увидеть в поле lastChangeDate.
     *
     * @param DateTime $dateFrom Начальная дата периода
     * @param int $flag Если параметр flag=0 (или не указан в строке запроса), при вызове API возвращаются данные у которых значение поля lastChangeDate (дата время обновления информации в сервисе) больше переданного в вызов значения параметра dateFrom. При этом количество возвращенных строк данных варьируется в интервале от 0 до примерно 100000
     * @return array|null
     */
    public function orders(DateTime $dateFrom, int $flag = 0): ?array
    {
        if (!in_array($flag, [0, 1])) {
            throw new InvalidArgumentException('Значение flag должно быть 0 или 1');
        }

        return $this->client->get('api/v1/supplier/orders', ['dateFrom' => $dateFrom, 'flag' => $flag]);
    }

    /**
     * Продажи
     *
     * **Важно:** гарантируется хранение данных по заказам не более 90 дней от даты заказа. Данные обновляются раз в 30 минут. Точное время обновления информации в сервисе можно увидеть в поле lastChangeDate.
     *
     * @param DateTime $dateFrom Начальная дата периода
     * @param int $flag Если параметр flag=0 (или не указан в строке запроса), при вызове API возвращаются данные у которых значение поля lastChangeDate (дата время обновления информации в сервисе) больше переданного в вызов значения параметра dateFrom. При этом количество возвращенных строк данных варьируется в интервале от 0 до примерно 100000
     * @return array|null
     */
    public function sales(DateTime $dateFrom, int $flag = 0): ?array
    {
        if (!in_array($flag, [0, 1])) {
            throw new InvalidArgumentException('Значение flag должно быть 0 или 1');
        }

        return $this->client->get('api/v1/supplier/sales', ['dateFrom' => $dateFrom]);
    }

    /**
     * Отчет о продажах по реализации.
     *
     * В отчете доступны данные за последние 3 месяца. В случае отсутствия данных за указанный период метод вернет null. Технический перерыв в работе метода: каждый понедельник с 3:00 до 14:00.
     *
     * @param DateTime $dateFrom Начальная дата периода
     * @param DateTime $dateTo Конечная дата периода
     * @param int $limit Максимальное количество строк отчета, возвращаемых методом. Не может быть более 100 000.
     * @param int $rrdid Уникальный идентификатор строки отчета. Необходим для получения отчета частями. Загрузку отчета нужно начинать с rrdid = 0 и при последующих вызовах API передавать в запросе значение rrd_id из последней строки, полученной в результате предыдущего вызова. Таким образом для загрузки одного отчета может понадобиться вызывать API до тех пор, пока количество возвращаемых строк не станет равным нулю.
     * @return array|null
     */
    public function reportDetailByPeriod(DateTime $dateFrom, DateTime $dateTo, int $limit = 1000, int $rrdid = 0): ?array
    {
        if ($dateFrom >= $dateTo) {
            throw new InvalidArgumentException('dateFrom должно быть меньше dateTo');
        }

        return $this->client->get('api/v1/supplier/reportDetailByPeriod', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'limit' => $limit, 'rrdid' => $rrdid]);
    }

    /**
     * Отчет по КиЗам.
     *
     * КИЗ — это контрольный идентификационный знак. Он представляет собой маркировку, похожую на QR-код, который проставляется на некоторых товарах. Его можно отсканировать с помощью специального приложения и убедиться в качестве и оригинальности товара. Сканирование КИЗов доступно как продавцу, так и покупателю, а также всем остальным участникам процесса продажи.
     *
     * @param DateTime $dateTime Время, с которого нужно получить отчет
     * @return array|null
     */
    public function exciseGoods(DateTime $dateTime): ?array
    {
        return $this->client->get('api/v1/supplier/excise-goods', ['dateFrom' => $dateTime]);
    }
}