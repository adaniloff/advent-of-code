<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

class AlwaysSuffixInterfaceRule extends AbstractAlwaysSuffixRule
{
    protected const NAME = 'App/always_suffix_interface';
    protected const SUFFIX = 'interface';
    protected const TOKEN = T_INTERFACE;

    public function deprecationString(string $oldName, string $newName): string
    {
        return "/**\n * @deprecated\n * @see {$newName}\n */\n";
    }

    public function todoString(string $oldName, string $newName): string
    {
        return "/**\n * @todo rename the file ({$oldName} -> {$newName}) before merging, then delete this comment.\n */\ninterface {$newName} extends {$oldName}\n{\n}\n\n";
    }
}
