<?php

declare(strict_types=1);

use \Dev\PhpCsFixer\App\{
    AlwaysSuffixTraitRule,
    AlwaysSuffixInterfaceRule,
    ArrangeActAssertRule,
    TestNSMustMatchCoveredClassNSRule,
    TestNSMustMatchDirectoryRule,
};

$finder = new PhpCsFixer\Finder();
$finder
    ->in(__DIR__)
    ->exclude(['bin', 'report', 'translations', 'var', 'vendor'])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers([
        new AlwaysSuffixTraitRule(),
        new AlwaysSuffixInterfaceRule(),
        new ArrangeActAssertRule(),
        new TestNSMustMatchCoveredClassNSRule(),
        new TestNSMustMatchDirectoryRule(),
    ])
    ->setRules([
        '@PSR1' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_alias_language_construct_call' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'void_return' => true,
        'global_namespace_import' => true,
        'declare_strict_types' => true,
    ] + [ // our custom rules
        'App/always_suffix_trait' => true,
        'App/always_suffix_interface' => true,
        'App/arrange_act_assert' => true,
        'App/test_ns_match_class_ns' => true,
        'App/test_ns_match_directory' => true,
    ] + [ // our other guidelines, overriding {@PSR1,@PhpCsFixer,@Symfony} default guidelines
        'group_import' => true,
        'single_import_per_statement' => false,
    ])
    ->setFinder($finder)
    ->setCacheFile('.php-cs-fixer.cache')
;
