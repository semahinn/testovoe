<?php

namespace App\Calculator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

abstract class Calculator implements CalculatorInterface
{
    protected array $params;

    public function __construct(array $params)
    {
        $this->validate($params);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    /**
     * Проверяет все входные параметры.
     *
     * @param array $params Массив входных параметров
     *
     * @return array Массив преобразовынных (проверенных) параметров
     */
    abstract protected function validate(array $params): array;

    /**
     * Возвращает формулу, по которой будет вычисляться значение
     * Это необходимо экземпляру ExpressionLanguage.
     *
     * @see ExpressionLanguage
     */
    abstract protected function getExpression(): string;

    /**
     * @inheritDoc
     */
    public function calculate(int $precision = 2, ?string $expression_override = null): float
    {
        $expression = new ExpressionLanguage();

        return round($expression->evaluate($expression_override ?: $this->getExpression(), $this->params), $precision);
    }
}
