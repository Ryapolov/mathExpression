<?php
namespace Ryapolov\Math;

use InvalidArgumentException;

/**
 * Class Expression
 * @package Ryapolov\Math
 */
class Expression
{
    public const AVAILABLE_CHARACTERS = ['(', ')', '+', ' ', '-', '*', '/', '\\d'];

    /**
     * Проверка простого математического выражения
     *
     * @param string $str
     * @return bool
     */
    public static function simpleCheck(string $str): bool
    {
        if (!preg_match('\'[' . implode('', self::AVAILABLE_CHARACTERS) . ']+\'', $str)) {
            throw new InvalidArgumentException('Пример содержит недопустимые символы ' . $str);
        }
        $str = preg_replace('/\s+/', '', $str);

        $countBrackets = 0;
        $arNotNextChar = [];
        $arStr = str_split($str);
        foreach ($arStr as $key => $char) {
            if ($key === 0 & in_array($char, ['*', '/', ')'])
                || ($key === count($arStr) & in_array($char, ['+', '-', '*', '/', '(']))
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
                    if ($arStr[$key - 1] === '(') {
                        $arNotNextChar = [')'];
                    }
                    $arNotNextChar[] = '(';
            }
        }
        return $countBrackets === 0;
    }
}
