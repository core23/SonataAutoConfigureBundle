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

namespace Nucleos\SonataAutoConfigureBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminExtensionsCompilerPass;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\SonataAutoConfigureExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\AttributedAdminExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\ExtensionWithoutOptions;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\GlobalExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\MultipleTargetedExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\TargetedWithPriorityExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class AutoConfigureExtensionsCompilerPassTest extends TestCase
{
    /**
     * @var AutoConfigureAdminExtensionsCompilerPass
     */
    private $autoconfigureExtensionsCompilerPass;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    protected function setUp(): void
    {
        $this->autoconfigureExtensionsCompilerPass = new AutoConfigureAdminExtensionsCompilerPass();
        $this->containerBuilder                    = new ContainerBuilder();

        $this->containerBuilder->setDefinition('annotation_reader', new Definition(AnnotationReader::class));
        $this->containerBuilder->registerExtension(new SonataAutoConfigureExtension());
    }

    /**
     * @dataProvider processData
     */
    public function testProcess(string $extensionServiceId, array $expectedTags = []): void
    {
        $this->loadConfig();

        $this->containerBuilder->setDefinition(
            $extensionServiceId,
            (new Definition($extensionServiceId))->addTag('sonata.admin.extension')->setAutoconfigured(true)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        static::assertInstanceOf(
            Definition::class,
            $extensionDefinition = $this->containerBuilder->getDefinition($extensionServiceId)
        );

        $actualTags = $extensionDefinition->getTag('sonata.admin.extension');
        foreach ($expectedTags as $i => $expectedTag) {
            static::assertArrayHasKey($i, $actualTags);
            static::assertSame($expectedTag, $actualTags[$i]);
        }
    }

    public function processData(): array
    {
        return [
            [
                ExtensionWithoutOptions::class,
            ],
            [
                GlobalExtension::class,
                [
                    [
                        'global' => true,
                    ],
                ],
            ],
            [
                TargetedWithPriorityExtension::class,
                [
                    [
                        'target'   => 'app.admin.category',
                        'priority' => 5,
                    ],
                ],
            ],
            [
                AttributedAdminExtension::class,
                [
                    [
                        'target'   => 'app.admin.category',
                        'priority' => 5,
                    ],
                ],
            ],
            [
                MultipleTargetedExtension::class,
                [
                    [
                        'target' => 'app.admin.category',
                    ],
                    [
                        'target' => 'app.admin.media',
                    ],
                ],
            ],
        ];
    }

    public function testProcessSkipAutoConfigured(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            TargetedWithPriorityExtension::class,
            (new Definition(TargetedWithPriorityExtension::class))->addTag('sonata.admin.extension')->setAutoconfigured(false)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition(TargetedWithPriorityExtension::class);
        $tag        = $definition->getTag('sonata.admin.extension');
        static::assertEmpty(reset($tag));
    }

    public function testProcessSkipIfAnnotationMissing(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            ExtensionWithoutOptions::class,
            (new Definition(ExtensionWithoutOptions::class))->addTag('sonata.admin.extension')->setAutoconfigured(true)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition(ExtensionWithoutOptions::class);
        $tag        = $definition->getTag('sonata.admin.extension');
        static::assertEmpty(reset($tag));
    }

    private function loadConfig(array $config = []): void
    {
        (new SonataAutoConfigureExtension())->load([
            'sonata_auto_configure' => $config,
        ], $this->containerBuilder);
    }
}
