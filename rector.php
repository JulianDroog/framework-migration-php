<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        // __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        //__DIR__ . '/webroot',
    ])
    ->withSets([SetList::PHP_84])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withTypeCoverageLevel(0);
