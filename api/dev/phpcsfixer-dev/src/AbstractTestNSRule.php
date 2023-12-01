<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\{Token, Tokens};
use SplFileInfo;

abstract class AbstractTestNSRule implements FixerInterface
{
    use FileTrait;
    use TokenTrait;

    protected const NAME = null;
    protected const SEPARATOR = '\\';
    protected const TOKENS = [];

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound(static::TOKENS);
    }

    public function isRisky(): bool
    {
        return false;
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

    abstract public function fix(SplFileInfo $file, Tokens $tokens): void;

    abstract public function getDefinition(): FixerDefinitionInterface;

    /**
     * Find a namespace from a Token set and return the array of Tokens matching the namespace.
     *
     * @return array<Token>
     */
    protected function findNamespace(Tokens $tokens): array
    {
        $index = (int) array_key_first($tokens->findGivenKind(T_NAMESPACE));
        $index = (int) $tokens->getNextMeaningfulToken($index);

        $nsTokens = [];
        while ($tokens[$index]->isGivenKind([T_NS_SEPARATOR, T_STRING])) {
            $nsTokens[$index] = $tokens[$index];
            $index = $tokens->getNextMeaningfulToken($index);
        }

        return $nsTokens;
    }
}
