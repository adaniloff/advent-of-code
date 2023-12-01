<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\{CodeSample, FixerDefinition, FixerDefinitionInterface};
use PhpCsFixer\Tokenizer\{Token, Tokens};
use SplFileInfo;

abstract class AbstractAlwaysSuffixRule implements FixerInterface
{
    use FileTrait;
    protected const NAME = null;
    protected const SUFFIX = null;
    protected const TOKEN = null;

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound([static::TOKEN]);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        /**
         * @var array<int, Token> $candidates
         */
        $candidates = [];

        /**
         * @var array<string> $existing
         */
        $existing = [];

        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(static::TOKEN)) {
                continue;
            }

            $token = $tokens[(int) $tokens->getNextMeaningfulToken($index)];
            if (str_ends_with($token->getContent(), $this->getCapitalizeSuffix())) {
                $existing[] = $token->getContent();
                continue;
            }

            $candidates[$index] = $token;
        }

        $token = null;
        foreach ($candidates as $above => $candidate) {
            $token ??= $candidate;

            if (in_array($candidate->getContent().$this->getCapitalizeSuffix(), $existing)) {
                continue;
            }

            $oldName = $candidate->getContent();
            $newName = $oldName.$this->getCapitalizeSuffix();

            $tokens->insertAt($above, [new Token($this->deprecationString($oldName, $newName))]);
            $tokens->insertAt($above, [new Token($this->todoString($oldName, $newName))]);

            break;
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            "Always suffix {$this->getSuffix()}s with the -{$this->getCapitalizeSuffix()} suffix.",
            [
                new CodeSample(
                    "<?php
    {$this->getSuffix()} Foo
    {
    }"
                ),
            ]
        );
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function getSuffix(): string
    {
        return strtolower(static::SUFFIX);
    }

    public function getCapitalizeSuffix(): string
    {
        return ucfirst($this->getSuffix());
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function supports(SplFileInfo $file): bool
    {
        return $this->isPhp($file);
    }

    abstract protected function deprecationString(string $oldName, string $newName): string;

    abstract protected function todoString(string $oldName, string $newName): string;
}
