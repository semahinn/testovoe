<?php

namespace App\Calculator;

interface VZRInterface extends CalculatorInterface
{
    /**
     * Возвращает коэффициент одного дня.
     */
    public function getOneDayCoefficient(): float;

    /**
     * Возвращает количество дней поездки.
     */
    public function getNumberOfDays(): int;

    /**
     * Возвращает текущий курс валюты.
     */
    public function getExchangeRate(): float;

    /**
     * Возвращает страховую сумму.
     */
    public function getSumInsured(): int;
}
