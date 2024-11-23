<?php
declare(strict_types=1);

namespace App\Rector\Set;

use Rector\Set\Contract\SetListInterface;

final class FrameworkSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const LARAVEL_11 = __DIR__ . '/../../../config/rector/sets/laravel11.php';
}
