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
final class Admin
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $managerType;

    /**
     * @var string
     */
    public $group;

    /**
     * @var bool
     */
    public $showInDashboard;

    /**
     * @var bool
     */
    public $showMosaicButton;

    /**
     * @var bool
     */
    public $keepOpen;

    /**
     * @var bool
     */
    public $onTop;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $labelTranslatorStrategy;

    /**
     * @var string
     */
    public $labelCatalogue;

    /**
     * @var string
     */
    public $translationDomain;

    /**
     * @var string
     */
    public $pagerType;

    /**
     * @var string
     */
    public $adminCode;

    /**
     * @var string
     */
    public $entity;

    /**
     * @var string
     */
    public $controller;

    /**
     * @var bool
     */
    public $autowireEntity = true;

    /**
     * @var array<string, string>
     */
    public $templates = [];

    /**
     * @var string[]
     */
    public $children = [];

    public function __construct(
        $data = [],
        ?string $label = null,
        ?string $managerType = null,
        ?string $group = null,
        ?bool $showInDashboard = null,
        ?bool $showMosaicButton = null,
        ?bool $keepOpen = null,
        ?bool $onTop = null,
        ?string $icon = null,
        ?string $labelTranslatorStrategy = null,
        ?string $labelCatalogue = null,
        ?string $translationDomain = null,
        ?string $pagerType = null,
        ?string $adminCode = null,
        ?string $entity = null,
        ?string $controller = null,
        ?bool $autowireEntity = null,
        ?array $templates = null,
        ?array $children = null
    ) {
        $this->label                   = $data['label']                   ?? $label;
        $this->managerType             = $data['managerType']             ?? $managerType;
        $this->group                   = $data['group']                   ?? $group;
        $this->showInDashboard         = $data['showInDashboard']         ?? $showInDashboard;
        $this->showMosaicButton        = $data['showMosaicButton']        ?? $showMosaicButton;
        $this->keepOpen                = $data['keepOpen']                ?? $keepOpen;
        $this->onTop                   = $data['onTop']                   ?? $onTop;
        $this->icon                    = $data['icon']                    ?? $icon;
        $this->labelTranslatorStrategy = $data['labelTranslatorStrategy'] ?? $labelTranslatorStrategy;
        $this->labelCatalogue          = $data['labelCatalogue']          ?? $labelCatalogue;
        $this->translationDomain       = $data['translationDomain']       ?? $translationDomain;
        $this->pagerType               = $data['pagerType']               ?? $pagerType;
        $this->adminCode               = $data['adminCode']               ?? $adminCode;
        $this->entity                  = $data['entity']                  ?? $entity;
        $this->controller              = $data['controller']              ?? $controller;
        $this->autowireEntity          = $data['autowireEntity']          ?? $autowireEntity;
        $this->templates               = $data['templates']               ?? $templates;
        $this->children                = $data['children']                ?? $children;
    }

    public function getOptions(): array
    {
        return array_filter(
            [
                'manager_type'              => $this->managerType,
                'group'                     => $this->group,
                'label'                     => $this->label,
                'show_in_dashboard'         => $this->showInDashboard,
                'show_mosaic_button'        => $this->showMosaicButton,
                'keep_open'                 => $this->keepOpen,
                'on_top'                    => $this->onTop,
                'icon'                      => $this->icon,
                'label_translator_strategy' => $this->labelTranslatorStrategy,
                'label_catalogue'           => $this->labelCatalogue,
                'pager_type'                => $this->pagerType,
            ],
            static function ($value): bool {
                return null !== $value;
            }
        );
    }
}
