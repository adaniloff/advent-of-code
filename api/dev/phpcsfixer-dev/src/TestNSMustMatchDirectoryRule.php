<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\FixerDefinition\{CodeSample, FixerDefinition, FixerDefinitionInterface};
use PhpCsFixer\Tokenizer\{Token, Tokens};
use SplFileInfo;

class TestNSMustMatchDirectoryRule extends AbstractTestNSRule
{
    protected const NAME = 'App/test_ns_match_directory';
    protected const TOKENS = [T_NAMESPACE, T_CLASS, T_FUNCTION];
    protected const RULE_MESSAGE = 'Tests namespace must follow their directory path.';

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $directoryPath = $this->extractDirectoryPath($file->getPath());

        $namespaceTokens = $this->findNamespace($tokens);
        $currentNamespace = $this->_concat($namespaceTokens);
        $expectedDirectoryPath = $this->extractDirectoryPath($currentNamespace);

        if ($expectedDirectoryPath === $directoryPath) {
            return;
        }

        if (!$setupMethodIndex = $this->findSetupMethod($tokens)) {
            $setupMethodIndex = $this->insertSetupMethod($tokens);
        }

        $setupMethodIndex += 2;
        if (!$this->isAlreadyMarked($tokens, $setupMethodIndex)) {
            $tokens->insertAt($setupMethodIndex, [new Token($this->asCommentNeedle()."\n\n        ")]);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            self::RULE_MESSAGE,
            [
                new CodeSample(
                    '<?php
    // the file is located in tests/Foo/Bar/FooTest.php
    namespace App\\Tests\\Foo\\Bar;

    class FooTest
    {
    }'
                ),
            ]
        );
    }

    private function isATestClass(Token $token): bool
    {
        return str_ends_with($token->getContent(), 'Test');
    }

    private function isSetupMethod(Token $token): bool
    {
        return 'setUpBeforeClass' === $token->getContent();
    }

    private function isAlreadyMarked(Tokens $tokens, int $index): bool
    {
        return str_contains(
            implode('', array_map(fn (?Token $token) => $token?->getContent() ?? '', array_slice($tokens->toArray(), $index, 7))),
            $this->asCommentNeedle()
        );
    }

    /**
     * Extract "test" directory path from a "file path" or a "namespace".
     * Ex:
     *     "\Dev\Package\Tests\Foo\Bar\FooBar" -> "Foo\Bar"
     *     "App\Tests\Foo\Bar\Baz\FooBar" -> "Foo\Bar\Baz"
     *     "/srv/api/tests/Infra/Shared/Elastic/" -> "Infra\Shared\Elastic".
     */
    private function extractDirectoryPath(string $path): string
    {
        $path = str_replace(self::SEPARATOR, '/', $path);
        preg_match('/[T|t]ests([\S]*)/', $path, $matches);
        $parts = count($matches) > 1 ? preg_split('#/#', $matches[1], -1, PREG_SPLIT_NO_EMPTY) : '';

        return empty($parts) ? '' : implode(self::SEPARATOR, $parts);
    }

    /**
     * Looks for test class method "setUpBeforeClass". If found, then its opening brace "index" is returned. Otherwise, null is returned.
     */
    private function findSetupMethod(Tokens $tokens): null|int
    {
        $setupTokens = $this->_filter($tokens, fn (Token $token) => $this->isSetupMethod($token));

        return empty($setupTokens) ? null : $this->findOpeningBrace($tokens, array_keys($setupTokens)[0]);
    }

    /**
     * Insert/Define the method "setUpBeforeClass" into the test class, and then return its opening brace "index".
     */
    private function insertSetupMethod(Tokens $tokens): int
    {
        $openingBrace = $this->findSetupInsertionIndex($tokens);

        $setupTokens = [
            new Token('public static function setUpBeforeClass(): void'."\n    "),
            new Token('{'),
            new Token("\n        "),
            new Token($this->asCommentNeedle()."\n    "),
            new Token('}'),
            new Token("\n\n    "),
        ];
        $tokens->insertAt($openingBrace + 2, $setupTokens);

        return $openingBrace + 3;
    }

    private function findSetupInsertionIndex(Tokens $tokens): int
    {
        $testClassTokens = $this->_filter($tokens, fn (Token $token) => $this->isATestClass($token));

        return $this->findOpeningBrace($tokens, array_keys($testClassTokens)[0]);
    }

    private function findOpeningBrace(Tokens $tokens, int $index): null|int
    {
        return $this->_strpos($tokens, needle: '{', position: $index);
    }

    private function asCommentNeedle(): string
    {
        return 'self::markTestIncomplete(\''.self::RULE_MESSAGE.'\');';
    }
}
