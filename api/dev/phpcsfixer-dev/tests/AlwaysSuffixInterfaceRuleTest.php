<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\Tests;

use Dev\PhpCsFixer\App\AlwaysSuffixInterfaceRule;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

/**
 * @group internal
 *
 * @coversDefaultClass \Dev\PhpCsFixer\App\AlwaysSuffixInterfaceRule
 *
 * @internal
 */
final class AlwaysSuffixInterfaceRuleTest extends TestCase
{
    /**
     * @dataProvider cases
     *
     * @covers \Dev\PhpCsFixer\App\AbstractAlwaysSuffixRule
     * @covers \Dev\PhpCsFixer\App\AlwaysSuffixInterfaceRule
     */
    public function testFixWorks(string $dir): void
    {
        $file = new SplFileInfo(__DIR__."/_data/AlwaysSuffixInterfaceRule/{$dir}/foo-before.txt");
        $tokens = Tokens::fromCode((string) file_get_contents($file->getPathname()));
        $expected = Tokens::fromCode((string) file_get_contents(__DIR__."/_data/AlwaysSuffixInterfaceRule/{$dir}/foo-after.txt"))->generateCode();

        $rule = new AlwaysSuffixInterfaceRule();
        $rule->fix($file, $tokens);

        $this->assertEquals($expected, $tokens->generateCode());
    }

    /**
     * @return array<string, array<string>>
     */
    public static function cases(): array
    {
        return [
            'normal test case -> the fix will apply' => ['default'],
            'multiple interfaces, the first one is valid -> the fix won\'t change it' => ['multiple-interface-default-valid'],
            'multiple interfaces, the first one is invalid but the file contains the valid one -> the fix won\'t change it' => ['multiple-interface-default-invalid'],
            'multiple interfaces, everything is wrong -> the fix will apply' => ['multiple-interface-all-wrong'],
        ];
    }
}
