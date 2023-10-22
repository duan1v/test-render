<?php

namespace Tests\Unit;

use App\Enums\FieldTypes;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    function toCamelCase($string, $startUp = false)
    {
        $words = preg_split('/[^a-zA-Z0-9]/', $string); // 分割字符串为单词或单词片段

        $camelCase = '';
        foreach ($words as $index => $word) {
            if ($index === 0 && !$startUp) {
                $camelCase .= strtolower($word); // 第一个单词保持小写
            } else {
                $camelCase .= ucfirst(strtolower($word)); // 其他单词首字母大写
            }
        }

        return $camelCase;
    }

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {

        $input = "hello woRld example";
//        $result = $this->toCamelCase($input, true);
//        $result = Str::camel($input);
        $result = Str::snake($input);
        echo $result;
        dd(1);
        $f = FieldTypes::forSelect();
        $f = FieldTypes::fromLength(1)->value;
        dd($f);
        $this->assertTrue(true);
    }
}
