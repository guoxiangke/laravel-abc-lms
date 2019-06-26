<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';


// PhpCsFixer\Finder::create()
//     ->in(app_path())
//     ->in(config_path())
//     ->in(database_path())
//     ->notPath(database_path('migrations'))
//     ->in(resource_path('lang'))
//     ->in(base_path('routes'))
//     ->in(base_path('tests'))

$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->exclude('storage')
    ->exclude('vendor')
    ->exclude('tests')
    ->exclude('public')
    ->exclude('resources')
    ->in(__DIR__ ."/app") 
    ->in(__DIR__ ."/database") 
    ->in(__DIR__ ."/routes") 
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new MattAllan\LaravelCodeStyle\Config())
    ->setFinder($finder)
    ->setRules([
        '@Laravel' => true,
    ])
    ->setUsingCache(false);
