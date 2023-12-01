<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use PhpCsFixer\Doctrine\Annotation\Tokens as DoctrineAnnotationTokens;
use PhpCsFixer\FixerDefinition\{CodeSample, FixerDefinition, FixerDefinitionInterface};
use PhpCsFixer\Tokenizer\{Token, Tokens};
use SplFileInfo;

class TestNSMustMatchCoveredClassNSRule extends AbstractTestNSRule
{
    protected const ANNOTATION = 'coversDefaultClass';
    protected const NAME = 'App/test_ns_match_class_ns';
    protected const TOKENS = [T_NAMESPACE, T_DOC_COMMENT, T_CLASS];

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $docs = $this->_str_contains($tokens->findGivenKind(T_DOC_COMMENT), self::ANNOTATION); // @phpstan-ignore-line - comment to remove after the following patch > https://github.com/phpstan/phpstan/issues/7782
        if (empty($docs)) {
            return;
        }

        $coveredClass = $this->extractCoveredFQCN(DoctrineAnnotationTokens::createFromDocComment(current($docs)));
        $expectedNamespace = $this->toTestScope($this->getNamespace($coveredClass));

        $namespaceTokens = $this->findNamespace($tokens);
        $currentNamespace = $this->_concat($namespaceTokens);

        if ($expectedNamespace !== $currentNamespace) {
            $tokens->overrideRange((int) array_key_first($namespaceTokens), (int) array_key_last($namespaceTokens), [new Token($expectedNamespace)]);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Tests namespace always match their covered class namespace.',
            [
                new CodeSample(
                    '<?php
    namespace App\\Tests\\Folder;
    /**
     * @coversDefaultClass App\\Folder\\Foo
     */
    class FooTest
    {
    }'
                ),
            ]
        );
    }

    private function extractCoveredFQCN(DoctrineAnnotationTokens $tokens): string
    {
        $index = 0;
        while (self::ANNOTATION !== $tokens[$index]->getContent()) {
            ++$index;
        }

        $namespace = $tokens[$index + 1]->getContent();
        $ns = preg_split("/(\r\n|\n|\r)/", $namespace);

        return $ns ? $ns[0] : $namespace;
    }

    /**
     * Return the namespace accordingly to a FQCN.
     */
    private function getNamespace(string $fqcn): string
    {
        foreach ([' '.self::SEPARATOR, ' '] as $search) {
            $fqcn = str_replace($search, '', $fqcn);
        }

        return implode(self::SEPARATOR, explode(self::SEPARATOR, $fqcn, -1));
    }

    /**
     * Transform a "production" scoped namespace to a "test" scoped namespace.
     * Ex:
     *  "\App\Foo\Bar\FooBar" -> "\App\Tests\Foo\Bar\FooBar"
     *  "\Dev\Package\App\Foo\Bar\FooBar" -> "\Dev\Package\Tests\Foo\Bar\FooBar".
     */
    private function toTestScope(string $namespace): string
    {
        $prefix = $this->findPackagePrefix($namespace);

        if (empty($prefix)) {
            return str_replace('App\\', 'App\\Tests\\', $namespace);
        }

        return str_replace($prefix.'App', $prefix.'Tests', $namespace);
    }

    /**
     * Return the prefix of the namespace if it belongs to another package than "src".
     */
    private function findPackagePrefix(string $namespace): string
    {
        if (str_starts_with($namespace, 'App\\')) {
            return '';
        }

        $pattern = str_ends_with($namespace, 'App') ? '/App/' : '/App\\\/';
        $splits = preg_split($pattern, $namespace, -1, PREG_SPLIT_NO_EMPTY);

        return $splits ? $splits[0] : '';
    }
}
