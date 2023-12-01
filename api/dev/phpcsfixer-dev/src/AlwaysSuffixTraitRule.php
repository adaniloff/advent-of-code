<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

class AlwaysSuffixTraitRule extends AbstractAlwaysSuffixRule
{
    protected const NAME = 'App/always_suffix_trait';
    protected const SUFFIX = 'trait';
    protected const TOKEN = T_TRAIT;

    public function deprecationString(string $oldName, string $newName): string
    {
        return "/**\n * @deprecated\n * @see {$newName}\n */\n";
    }

    public function todoString(string $oldName, string $newName): string
    {
        return "/**\n * @todo rename the file ({$oldName} -> {$newName}) before merging, then delete this comment.\n */\ntrait {$newName}\n{\n    use {$oldName};\n}\n\n";
    }
}
