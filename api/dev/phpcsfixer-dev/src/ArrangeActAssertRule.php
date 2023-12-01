<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\{CodeSample, FixerDefinition, FixerDefinitionInterface};
use PhpCsFixer\Tokenizer\{Token, Tokens};
use SplFileInfo;

class ArrangeActAssertRule implements FixerInterface
{
    use FileTrait;
    use TokenTrait;

    protected const NAME = 'App/arrange_act_assert';
    protected const MESSAGE = 'AAA must be implemented.';

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound([T_CLASS]);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(T_FUNCTION)) {
                continue;
            }

            $index = $tokens->getNextMeaningfulToken($index);
            if (null === $index || !$this->isATestMethod($tokens[$index])) {
                continue;
            }

            // get opening brace
            $char = null;
            do {
                if ($index = $tokens->getNextMeaningfulToken($index)) {
                    $char = $tokens[$index]->getContent();
                }
            } while ('{' !== $char && $index);
            if ('{' !== $char) {
                continue;
            }
            $openingBrace = $index;

            // get closing brace
            $braces = 0;
            do {
                $char = null;
                if ($index = $tokens->getNextMeaningfulToken($index)) {
                    $char = $tokens[$index]->getContent();
                }
                if ('{' === $char) {
                    ++$braces;
                }
                if ('}' === $char) {
                    --$braces;
                }
            } while (-1 !== $braces && $index);
            $closingBrace = $index;

            $index = $tokens->getNextMeaningfulToken($openingBrace);
            if (null === $index || $this->isAlreadyMarked($index, $tokens)) {
                continue;
            }

            $this->cleanComments($openingBrace, $closingBrace, $tokens, comments: ['Arrange', 'Act', 'Assert']);
            if (!$this->isAAA($openingBrace, $closingBrace, $tokens)) {
                $tokens->insertAt(
                    $openingBrace + 2,
                    [new Token('$this->markTestIncomplete(\''.self::MESSAGE.'\');'."\n\n        ")]
                );
            }
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Always implement the AAA (Arrange Act Assert) convention.',
            [
                new CodeSample(
                    '<?php
    class FooTest extends \\PHPUnit\\Framework\\TestCase
    {
        public function testMethodWith3LinesOrLess(): void
        {
            $arrange = 1;
            $act = ++$arrange;
            $this->assertEquals(2, $act);
        }

        public function testMethodWith2BlankLines(): void
        {
            $arrange = 1;
            $arrange++;

            $act = $arrange * 2;

            $this->assertNotEquals(3, $act);
            $this->assertEquals(4, $act);
        }

        public function testMethodWithComments(): void
        {
            // Arrange
            $arrange = 0;

            // Act
            for ($i = 0; $i < 10; $i++) {
                $arrange += $i;
            }

            $act = $arrange * 2;

            // Assert
            $this->assertNotNull($act);

            $this->assertNotEquals(0, $act);
        }
    }'
                ),
            ]
        );
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function supports(SplFileInfo $file): bool
    {
        return $this->isPhp($file) && $this->isTest($file);
    }

    private function isATestMethod(Token $token): bool
    {
        return str_starts_with($token->getContent(), 'test');
    }

    private function isAlreadyMarked(int $index, Tokens $tokens): bool
    {
        return '$this->markTestIncomplete(\''.self::MESSAGE.'\');' === $this->_concat(
            array_slice($tokens->toArray(), $index, 7)
        );
    }

    private function isAAA(int $openingBraceIndex, int $closingBraceIndex, Tokens $tokens): bool
    {
        if ($this->containsCodeLines($openingBraceIndex, $closingBraceIndex, $tokens, max: 3)) {
            return true;
        }

        if ($this->containsAllCommentsOnce($openingBraceIndex, $closingBraceIndex, $tokens, comments: [
            'Arrange',
            'Act',
            'Assert',
        ])) {
            return true;
        }

        if (
            $this->containsBlankLines($openingBraceIndex, $closingBraceIndex, $tokens, exactly: 2)
            && $this->containsNoComments($openingBraceIndex, $closingBraceIndex, $tokens, comments: [
                'Arrange',
                'Act',
                'Assert',
            ])) {
            return true;
        }

        return false;
    }

    private function containsCodeLines(int $openingBraceIndex, int $closingBraceIndex, Tokens $tokens, int $max): bool
    {
        $blankLineCount = 0;
        $index = $openingBraceIndex + 2;
        do {
            str_contains($tokens[$index]->getContent(), ';')
            && str_contains($tokens[$index + 1]->getContent(), "\n")
            && $blankLineCount++;
        } while ($index++ < $closingBraceIndex);

        return $blankLineCount <= $max;
    }

    private function containsBlankLines(
        int $openingBraceIndex,
        int $closingBraceIndex,
        Tokens $tokens,
        int $exactly
    ): bool {
        $blankLineCount = 0;
        $index = $openingBraceIndex + 2;
        do {
            str_contains($tokens[$index]->getContent(), "\n\n") && $blankLineCount++;
        } while ($index++ < $closingBraceIndex);

        return $exactly === $blankLineCount;
    }

    /**
     * @param array<string> $comments
     */
    private function containsAllCommentsOnce(
        int $openingBraceIndex,
        int $closingBraceIndex,
        Tokens $tokens,
        array $comments
    ): bool {
        return $this->nbCommentFounds($openingBraceIndex, $closingBraceIndex, $tokens, $comments) === count($comments);
    }

    /**
     * @param array<string> $comments
     */
    private function containsNoComments(
        int $openingBraceIndex,
        int $closingBraceIndex,
        Tokens $tokens,
        array $comments
    ): bool {
        return 0 === $this->nbCommentFounds($openingBraceIndex, $closingBraceIndex, $tokens, $comments);
    }

    /**
     * @param array<int, string> $comments
     */
    private function nbCommentFounds(
        int $openingBraceIndex,
        int $closingBraceIndex,
        Tokens $tokens,
        array $comments
    ): int {
        $count = 0;
        $this->mapComments(
            $openingBraceIndex,
            $closingBraceIndex,
            $tokens,
            $comments,
            static function () use (&$count): void {
                ++$count;
            }
        );

        return $count;
    }

    /**
     * @param array<int, string> $comments
     */
    private function cleanComments(int $openingBraceIndex, int $closingBraceIndex, Tokens $tokens, array $comments): void
    {
        $this->mapComments($openingBraceIndex, $closingBraceIndex, $tokens, $comments, static function ($comment) {
            return new Token('// '.ucfirst(strtolower($comment)));
        });
    }

    /**
     * @param array<int, string> $comments
     */
    private function mapComments(
        int $openingBraceIndex,
        int $closingBraceIndex,
        Tokens $tokens,
        array $comments,
        callable $map
    ): void {
        $index = $openingBraceIndex + 2;
        do {
            $content = $tokens[$index]->getContent();
            foreach ($comments as $comment) {
                if (preg_match("/\\/\\/\\s*{$comment}$/i", $content)) {
                    $tokens[$index] = $map($comment) ?? $tokens[$index];
                }
            }
        } while ($index++ < $closingBraceIndex);
    }
}
