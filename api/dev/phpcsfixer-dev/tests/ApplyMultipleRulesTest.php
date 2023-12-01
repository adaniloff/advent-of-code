<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\Tests;

use Dev\PhpCsFixer\App\{TestNSMustMatchCoveredClassNSRule, TestNSMustMatchDirectoryRule};
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

/**
 * @group internal
 *
 * @internal
 *
 * @coversNothing
 */
final class ApplyMultipleRulesTest extends TestCase
{
    /**
     * @dataProvider cases
     *
     * @covers \Dev\PhpCsFixer\App\AbstractTestNSRule
     * @covers \Dev\PhpCsFixer\App\TestNSMustMatchCoveredClassNSRule
     * @covers \Dev\PhpCsFixer\App\TestNSMustMatchDirectoryRule
     */
    public function testFixWorks(string $dir): void
    {
        // Arrange
        $file = new SplFileInfo(__DIR__."/_data/ApplyMultipleRules/{$dir}/foo-before.txt");
        $tokens = Tokens::fromCode((string) file_get_contents($file->getPathname()));
        $expected = Tokens::fromCode((string) file_get_contents(__DIR__."/_data/ApplyMultipleRules/{$dir}/foo-after.txt"))->generateCode();

        $rules = [
            new TestNSMustMatchCoveredClassNSRule(),
            new TestNSMustMatchDirectoryRule(),
        ];

        // Act
        foreach ($rules as $rule) {
            $rule->fix($file, $tokens);
            $tokens = Tokens::fromCode($tokens->generateCode());
        }

        // Assert
        $this->assertEquals($expected, $tokens->generateCode());
    }

    /**
     * @return array<string, array<string>>
     */
    public static function cases(): array
    {
        return [
            'mismatch btw covered class and directory path -> only TestNSMustMatchCoveredClassNSRule->fix will apply' => ['MismatchBtwDirectoryAndCoveredClass'],
            'mismatch btw namespace and covered class and directory path -> all the fixes will apply' => ['MismatchBtwNsAndDirectoryAndCoveredClass'],
        ];
    }
}
