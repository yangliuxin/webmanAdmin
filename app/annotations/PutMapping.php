<?php

declare(strict_types=1);

namespace plugin\webmanAdmin\app\annotations;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class PutMapping extends Mapping
{
    public function __construct(...$value)
    {
        $this->path = $value[0]['value'] ?? '';
    }

    public function getMethods()
    {
        return 'put';
    }
}
