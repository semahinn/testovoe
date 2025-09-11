<?php

namespace App\Calculator;

/**
 * Рассчитывает стоимость страховки для выезжающих за рубеж (ВЗР)
 * на основе следующих параметров:
 *  'sum_insured' - Страховая сумма
 *  'date_start' - Дата начала поездки (YYYY-mm-dd)
 *  'date_end' - Дата окончания поездки (YYYY-mm-dd)
 *  'currency' - Код валюты.
 */
class VZR extends Calculator implements VZRInterface
{
    /**
     * @inheritDoc
     */
    public function getOneDayCoefficient(): float
    {
        return parent::get('one_day_coefficient');
    }

    /**
     * @inheritDoc
     */
    public function getNumberOfDays(): int
    {
        return parent::get('number_of_days');
    }

    /**
     * @inheritDoc
     */
    public function getExchangeRate(): float
    {
        return parent::get('exchange_rate');
    }

    /**
     * @inheritDoc
     */
    public function getSumInsured(): int
    {
        return parent::get('sum_insured');
    }

    /**
     * @inheritDoc
     */
    protected function validate(array $params): array
    {
        if (empty($params['sum_insured']) || (int) $params['sum_insured'] != $params['sum_insured'] || (int) $params['sum_insured'] < 1) {
            throw new \InvalidArgumentException('Страховая сумма должна быть целым положительным числом');
        }
        $this->params['sum_insured'] = (int) $params['sum_insured'];

        $one_day_coefficient = $this->checkOneDayCoefficient($params['sum_insured']);
        if (false === $one_day_coefficient) {
            throw new \InvalidArgumentException('Недопустимая страховая сумма, невозможно вычислить коэффициент одного дня');
        }
        $this->params['one_day_coefficient'] = $one_day_coefficient;

        if (empty($params['currency'])) {
            throw new \InvalidArgumentException('Код валюты не указан');
        }
        $this->params['currency'] = $params['currency'];
        $exchange_rate = $this->checkCurrency($params['currency']);
        if (false === $exchange_rate) {
            throw new \InvalidArgumentException('Не удалось определить теущий курс валюты');
        }
        $this->params['exchange_rate'] = $exchange_rate;

        if (empty($params['date_start'])) {
            throw new \InvalidArgumentException('Дата начала поездки не указана');
        }

        if (empty($params['date_end'])) {
            throw new \InvalidArgumentException('Дата конца поездки не указана');
        }

        $start = \DateTime::createFromFormat('Y-m-d', $params['date_start']);
        $end = \DateTime::createFromFormat('Y-m-d', $params['date_end']);

        $interval = $end->diff($start);
        if (!$interval->invert) {
            throw new \InvalidArgumentException('Дата конца поездки не может быть раньше даты начала');
        }

        $this->params['date_start'] = $params['date_start'];
        $this->params['date_end'] = $params['date_end'];
        $this->params['number_of_days'] = (int) $interval->format('%a');

        return $this->params;
    }

    /**
     * @inheritDoc
     */
    protected function getExpression(): string
    {
        return 'one_day_coefficient * number_of_days';
    }

    protected function checkOneDayCoefficient(int $sum_insured): float|false
    {
        // Я так понял, что другие страховые суммы запрещены (кроме 30000 и 50000)
        if (30000 == $sum_insured) {
            return 0.6;
        } elseif (50000 == $sum_insured) {
            return 0.8;
        } else {
            return false;
        }
    }

    protected function checkCurrency(string $currency): float|false
    {
        $currency = strtolower($currency);
        // Заглушка
        // Здесь должна быть логика, определяющая на основе $currency
        // значение текущего курса валюты
        if ('usd' == $currency) {
            return 84.50;
        } elseif ('eur' == $currency) {
            return 99.15;
        } else {
            return false;
        }
    }
}
