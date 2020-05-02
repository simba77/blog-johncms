<?php

declare(strict_types=1);

use Blog\Utils\SectionPathCache;

return [
    'dependencies' => [
        'factories' => [
            SectionPathCache::class => SectionPathCache::class,
        ],
    ],
];
