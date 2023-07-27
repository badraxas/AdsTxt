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
    ])
    ->setFinder($finder);
