<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\Tokenizer\{Token, Tokens};

trait TokenTrait
{
    /**
     * Concatenate Tokens content into one string.
     *
     * @param array<Token>|Tokens $tokens*
     */
    protected function _concat(array|Tokens $tokens): string
    {
        $tokens = $tokens instanceof Tokens ? $tokens->toArray() : $tokens;

        return implode('', array_map(fn (?Token $token) => $token?->getContent() ?? '', $tokens));
    }

    /**
     * Filter Tokens content and return only those which matches with (contains) the needle.
     *
     * @param array<Token>|Tokens $tokens
     *
     * @return array<int, Token>
     */
    protected function _str_contains(array|Tokens $tokens, string $needle): array
    {
        return $this->_filter($tokens, fn (?Token $token) => str_contains($token?->getContent() ?? '', $needle));
    }

    /**
     * Apply a filter to a set of Tokens.
     *
     * @param array<Token>|Tokens $tokens
     *
     * @return array<int, Token>
     */
    protected function _filter(array|Tokens $tokens, callable $fn): array
    {
        $tokens = $tokens instanceof Tokens ? $tokens->toArray() : $tokens;

        return array_filter($tokens, $fn);
    }

    /**
     * Return the index of the first occurence of a character after a position, return null if no index is found.
     *
     * @param array<Token>|Tokens $tokens
     */
    protected function _strpos(array|Tokens $tokens, string $needle, int $position = 0): ?int
    {
        $tokens = $this->_str_contains($tokens, $needle);

        reset($tokens);
        do {
            $index = key($tokens);
            next($tokens);
        } while (false !== $index && $index < $position);

        return $index ?: null;
    }
}
