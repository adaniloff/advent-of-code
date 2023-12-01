<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\Tests;

use Dev\PhpCsFixer\App\TestNSMustMatchDirectoryRule;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

/**
 * @group internal
 *
 * @coversDefaultClass \Dev\PhpCsFixer\App\TestNSMustMatchDirectoryRule
 *
 * @internal
 */
final class TestNSMustMatchDirectoryRuleTest extends TestCase
{
    /**
     * @covers ::supports
     */
    public function testSupportsTestClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/TestNSMustMatchDirectoryRule/supports/DummySupportTest.php');
        $isSupported = (new TestNSMustMatchDirectoryRule())->supports($file);
        $this->assertTrue($isSupported);
    }

    /**
     * @covers ::supports
     */
    public function testDoesNotSupportRegularClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/TestNSMustMatchDirectoryRule/supports/DummySupport.php');
        $isSupported = (new TestNSMustMatchDirectoryRule())->supports($file);
        $this->assertFalse($isSupported);
    }

    /**
     * @dataProvider cases
     *
     * @covers \Dev\PhpCsFixer\App\AbstractTestNSRule
     * @covers \Dev\PhpCsFixer\App\TestNSMustMatchDirectoryRule
     */
    public function testFixWorks(string $dir): void
    {
        $file = new SplFileInfo(__DIR__."/_data/TestNSMustMatchDirectoryRule/{$dir}/foo-before.txt");
        $tokens = Tokens::fromCode((string) file_get_contents($file->getPathname()));
        $expected = Tokens::fromCode((string) file_get_contents(__DIR__."/_data/TestNSMustMatchDirectoryRule/{$dir}/foo-after.txt"))->generateCode();

        $rule = new TestNSMustMatchDirectoryRule();
        $rule->fix($file, $tokens);

        $this->assertEquals($expected, $tokens->generateCode());
    }

    /**
     * @return array<string, array<string>>
     */
    public static function cases(): array
    {
        return [
            'match between namespace and directory -> the fix won\'t change it' => ['default'],
            'invalid test -> the fix will apply' => ['default-invalid'],
            'invalid test (w/ annotation on test) -> the fix will apply' => ['invalid-with-annotation'],
            'invalid test (w/ two classes definition) -> the fix will apply' => ['invalid-with-multiple-classes'],
            'invalid test (w/o a setup function) -> the fix will apply' => ['invalid-with-no-setup-function'],
            'invalid test (w/ a test function before setup function) -> the fix will apply' => ['invalid-with-test-function-before-setup'],
        ];
    }
}
