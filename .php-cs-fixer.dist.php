<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,
    'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
    'concat_space' => ['spacing' => 'one'],
    'no_spaces_around_offset' => true,
    'array_syntax' => ['syntax' => 'short'],
    'function_typehint_space' => true,
    'no_empty_statement' => true,
    'no_leading_namespace_whitespace' => true,
    'no_superfluous_phpdoc_tags' => [
        'allow_mixed' => false,
        'allow_unused_params' => false,
        'remove_inheritdoc' => false,
    ],
    'no_unused_imports' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'phpdoc_trim' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'ordered_class_elements' => ['order' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'phpunit', 'method_public', 'method_protected', 'method_private']],
    'no_empty_statement' => true,
    'array_indentation' => true,
    'binary_operator_spaces' => [
        'default' => 'single_space'
    ],
    'no_singleline_whitespace_before_semicolons' => true,
    'blank_line_before_statement' => [
        'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try']
    ],
    'class_attributes_separation' => [
        'elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']
    ],
    'combine_consecutive_issets' => true,
    'no_mixed_echo_print' => [
        'use' => 'echo',
    ],
    'no_short_bool_cast' => true,
    'no_useless_return' => true,
    'normalize_index_brace' => true,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
        // __DIR__ . '/nova-components',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
