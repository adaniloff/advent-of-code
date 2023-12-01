<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\Tests;

use Dev\PhpCsFixer\App\ArrangeActAssertRule;
use PhpCsFixer\Tokenizer\{Token, Tokens};
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SplFileInfo;

/**
 * @group internal
 *
 * @coversDefaultClass \Dev\PhpCsFixer\App\ArrangeActAssertRule
 *
 * @internal
 */
final class ArrangeActAssertRuleTest extends TestCase
{
    /**
     * @covers ::isATestMethod
     */
    public function testIsATestMethod(): void
    {
        $rule = new ArrangeActAssertRule();
        $closure = new ReflectionMethod($rule, 'isATestMethod');

        $isATestMethod = $closure->invoke($rule, new Token('testATestMethod'));

        $this->assertTrue($isATestMethod);
    }

    /**
     * @covers ::isATestMethod
     */
    public function testIsNotATestMethod(): void
    {
        $rule = new ArrangeActAssertRule();
        $closure = new ReflectionMethod($rule, 'isATestMethod');

        $isATestMethod = $closure->invoke($rule, new Token('aRegularMethod'));

        $this->assertFalse($isATestMethod);
    }

    /**
     * @covers ::isAlreadyMarked
     *
     * @dataProvider tokens
     */
    public function testIsAlreadyMarked(bool $expected, int $index, Tokens $tokens): void
    {
        $rule = new ArrangeActAssertRule();
        $closure = new ReflectionMethod($rule, 'isAlreadyMarked');

        $isAlreadyMarked = $closure->invoke($rule, $index, $tokens);

        $this->assertEquals($expected, $isAlreadyMarked);
    }

    /**
     * @return array<mixed>
     */
    public static function tokens(): array
    {
        $tokens = [
            new Token('{'),
            new Token("\r"),
            new Token('$this'),
            new Token('->'),
            new Token('markTestIncomplete'),
            new Token('('),
            new Token('\'AAA must be implemented.\''),
            new Token(')'),
            new Token(';'),
            new Token("\r"),
            new Token('}'),
        ];

        return [
            'valid case of tokens suite' => [true, 2, Tokens::fromArray($tokens)],
            'invalid case of tokens suite' => [false, 2, Tokens::fromArray(array_reverse($tokens))],
            'invalid index' => [false, 1, Tokens::fromArray($tokens)],
        ];
    }

    /**
     * @covers ::supports
     */
    public function testSupportsTestClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/ArrangeActAssertRule/supports/DummySupportTest.php');
        $isSupported = (new ArrangeActAssertRule())->supports($file);
        $this->assertTrue($isSupported);
    }

    /**
     * @covers ::supports
     */
    public function testDoesNotSupportRegularClasses(): void
    {
        $file = new SplFileInfo(__DIR__.'/_data/ArrangeActAssertRule/supports/DummySupport.php');
        $isSupported = (new ArrangeActAssertRule())->supports($file);
        $this->assertFalse($isSupported);
    }

    /**
     * @dataProvider cases
     *
     * @covers \Dev\PhpCsFixer\App\ArrangeActAssertRule
     */
    public function testFixWorks(string $dir): void
    {
        $file = new SplFileInfo(__DIR__."/_data/ArrangeActAssertRule/{$dir}/foo-before.txt");
        $tokens = Tokens::fromCode((string) file_get_contents($file->getPathname()));
        $expected = Tokens::fromCode((string) file_get_contents(__DIR__."/_data/ArrangeActAssertRule/{$dir}/foo-after.txt"))->generateCode();

        $rule = new ArrangeActAssertRule();
        $rule->fix($file, $tokens);

        $this->assertEquals($expected, $tokens->generateCode());
    }

    /**
     * @return array<string, array<string>>
     */
    public static function cases(): array
    {
        return [
            'not a test method -> the fix won\'t change it' => ['not-a-test-method'],
            'single block case' => ['single-block-case'],
            'multiple block, no comments case' => ['multiple-blocks-case'],
            'multiple block, with comments case' => ['multiple-commented-blocks-case'],
            'single method case, non regression' => ['single-method-case'],
            'multiple braces in method, non regression' => ['multiple-braces-case'],
        ];
    }
}
