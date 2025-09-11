<?php

namespace App\Tests\Calculator;

use App\Calculator\VZR;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function test1()
    {
        // Представим, что я получил из запроса данные для
        // вычисления стоимости страховки для выезжающих за рубеж (ВЗР)
        $params = [
            'sum_insured' => 30000,
            'date_start' => '2025-01-01',
            'date_end' => '2025-01-25',
            'currency' => 'USD',
        ];

        $calculator = new VZR($params);

        $result = [
            // Общая стоимость в валюте страхования (one_day_coefficient * number_of_days)
            'value_in_currency' => $calculator->calculate(),
            // Общая стоимость в рублях
            'value_in_rubles' => $calculator->calculate(2, 'one_day_coefficient * number_of_days * exchange_rate'),
            // Количество дней поездки
            'number_of_days' => $calculator->getNumberOfDays(),
            // Коэффициент одного дня
            'one_day_coefficient' => $calculator->getOneDayCoefficient(),
            // Курс валюты на сегодняшний день
            'exchange_rate' => $calculator->getExchangeRate(),
            // Страховая сумма
            'sum_insured' => $calculator->getSumInsured(),
        ];

        $this->assertEquals($result, [
            // Общая стоимость в валюте страхования (one_day_coefficient * number_of_days)
            'value_in_currency' => 14.4,
            // Общая стоимость в рублях
            'value_in_rubles' => 1216.8,
            // Количество дней поездки
            'number_of_days' => 24,
            // Коэффициент одного дня
            'one_day_coefficient' => 0.6,
            // Курс валюты на сегодняшний день
            'exchange_rate' => 84.5,
            // Страховая сумма
            'sum_insured' => 30000,
        ]);

        // Представим, что дальше этот ответ возвращается в виде json

        // Не стал писать тесты на неудачу, смысл и так понятен
    }
}
