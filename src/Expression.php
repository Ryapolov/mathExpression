<?php
namespace Ryapolov\Math;

use InvalidArgumentException;

/**
 * Class Expression
 * @package Ryapolov\Math
 */
class Expression
{
    private const AVAILABLE_CHARACTERS = ['\\(', '\\)', '\\+', ' ', '\\-', '\\*', '/', '\\d'];

    /**
     * Проверка простого математического выражения
     *
     * @param string $strExpression
     * @return bool
     */
    public static function simpleCheck(string $strExpression): bool
    {
        $strExpression = preg_replace('/\s+/', '', $strExpression);

        if (empty($strExpression)) {
            throw new InvalidArgumentException('В функцию передана пустая строка');
        }
        if (preg_match('\'[^ ' . implode('', self::AVAILABLE_CHARACTERS) . ']+\'', $strExpression, $ma)) {
            throw new InvalidArgumentException('Пример содержит недопустимые символы ' . $strExpression);
        }

        $countBrackets = 0;
        $arNotNextChar = [];
        $arExpression = str_split($strExpression);
        foreach ($arExpression as $key => $char) {
            if ($key === 0 & in_array($char, ['*', '/', ')'])
                || ($key === count($arExpression) & in_array($char, ['+', '-', '*', '/', '(']))
                || in_array($char, $arNotNextChar, true)
                || (isset($arNotNextChar['not_num']) & is_numeric($char))) {
                return false;
            }

            switch ($char) {
                case '(':
                    ++$countBrackets;
                    $arNotNextChar = [')', '/', '*'];
                    break;
                case ')':
                    $arNotNextChar = ['(', 'not_num' => true];
                    --$countBrackets;
                    break;
                case '+':
                case '-':
                case '*':
                case '/':
                    $arNotNextChar = ['+', '-', '*', '/', ')', '0'];
                    break;
                default:
                    $arNotNextChar = ['('];
                    if ($arExpression[$key - 1] === '(') {
                        $arNotNextChar[] = [')'];
                    }
            }
        }
        return $countBrackets === 0;
    }
}
