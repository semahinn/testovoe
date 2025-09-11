<?php

namespace App\Calculator;

/**
 * Вычисляет значение выражения на
 * основе входных параметров.
 */
interface CalculatorInterface
{
    /**
     * Возвращает значение параметра.
     *
     * @param string $key Идентифткатор параметра
     *
     * @return mixed Значение параметра
     */
    public function get(string $key): mixed;

    /**
     * Возвращает результат вычисления.
     *
     * @param int $precision Количество цифр после запятой
     *
     * @return float Результат вычисления
     */
    public function calculate(int $precision = 2, string $expression_override = null): float;
}
