<?php

declare(strict_types=1);

/*
 * This file is part of the SonataAutoConfigureBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SonataAutoConfigureBundle\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AdminExtension
{
    /**
     * @var bool
     */
    public $global;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var string[]
     */
    public $target;

    public function __construct(
        $data = [],
        ?bool $global = null,
        ?int $priority = null,
        ?array $target = null
    ) {
        $this->global   = $data['global']   ?? $global;
        $this->priority = $data['priority'] ?? $priority;
        $this->target   = $data['target']   ?? $target;
    }

    public function getOptions(): array
    {
        return array_filter(
            [
                'global'   => $this->global,
                'priority' => $this->priority,
                'target'   => $this->target,
            ],
            static function ($value): bool {
                return null !== $value;
            }
        );
    }
}
