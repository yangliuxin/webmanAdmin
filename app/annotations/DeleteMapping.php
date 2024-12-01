<?php

declare(strict_types=1);

namespace plugin\webmanAdmin\app\annotations;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class DeleteMapping extends Mapping
{
    public function __construct(...$value)
    {
        $this->path = $value[0]['value'] ?? '';
    }

    /**
     * @return string
     */
    public function getMethods(): string
    {
        return 'delete';
    }
}
