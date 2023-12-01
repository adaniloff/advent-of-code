<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\Tests;

use Dev\PhpCsFixer\App\TestNSMustMatchCoveredClassNSRule;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

/**
 * @group internal
 *
 * @coversDefaultClass \Dev\PhpCsFixer\App\TestNSMustMatchCoveredClassNSRule
 *
 * @internal
 */
final class TestNSMustMatchCoveredClassNSRuleTest extends TestCase
{
    /**
     * @covers ::supports
     */
    public function testSupportsTestClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/TestNSMustMatchCoveredClassNSRule/supports/DummySupportTest.php');
        $isSupported = (new TestNSMustMatchCoveredClassNSRule())->supports($file);
        $this->assertTrue($isSupported);
    }

    /**
     * @covers ::supports
     */
    public function testDoesNotSupportRegularClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/TestNSMustMatchCoveredClassNSRule/supports/DummySupport.php');
        $isSupported = (new TestNSMustMatchCoveredClassNSRule())->supports($file);
        $this->assertFalse($isSupported);
    }

    /**
     * @dataProvider cases
     *
     * @covers \Dev\PhpCsFixer\App\AbstractTestNSRule
     * @covers \Dev\PhpCsFixer\App\TestNSMustMatchCoveredClassNSRule
     */
    public function testFixWorks(string $dir): void
    {
        $file = new SplFileInfo(__DIR__."/_data/TestNSMustMatchCoveredClassNSRule/{$dir}/foo-before.txt");
        $tokens = Tokens::fromCode((string) file_get_contents($file->getPathname()));
        $expected = Tokens::fromCode((string) file_get_contents(__DIR__."/_data/TestNSMustMatchCoveredClassNSRule/{$dir}/foo-after.txt"))->generateCode();

        $rule = new TestNSMustMatchCoveredClassNSRule();
        $rule->fix($file, $tokens);

        $this->assertEquals($expected, $tokens->generateCode());
    }

    /**
     * @return array<string, array<string>>
     */
    public static function cases(): array
    {
        return [
            'match between namespaces -> the fix won\'t change it' => ['namespace-default-valid'],
            'invalid test namespace -> the fix will apply' => ['namespace-default-invalid'],
            '(dev) match between namespaces -> the fix won\'t change it' => ['dev-namespace-default-valid'],
            '(dev) match between namespaces (w/ sub-folder) -> the fix won\'t change it' => ['dev-namespace-multi-sub-folder-valid'],
            '(dev) invalid test namespace -> the fix will apply' => ['dev-namespace-default-invalid'],
            'no class covered -> the fix won\'t change it' => ['namespace-no-class-covered'],
        ];
    }
}
