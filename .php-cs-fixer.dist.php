<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->name('*.php')
    ->notName('autoload.php')
    ->notName('php-cs-fixer.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'use_arrow_functions' => true,
        'ordered_class_elements' => ['sort_algorithm' => 'alpha'],
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
