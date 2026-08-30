<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12:risky' => true,
        '@PHP8x4Migration:risky' => true,
        '@PER-CS3x0:risky' => true,
        '@PHPUnit11x0Migration:risky' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'explicit_string_variable' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_to_return_type' => true,
        'class_reference_name_casing' => true,
        'trailing_comma_in_multiline' =>
        [
            'after_heredoc' => true,
            'elements' => ['array_destructuring', 'arrays']
        ],
        'function_declaration' => [
            'closure_fn_spacing' => 'one'
        ]


    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in(__DIR__ . '/Classes')
    )
    ;
