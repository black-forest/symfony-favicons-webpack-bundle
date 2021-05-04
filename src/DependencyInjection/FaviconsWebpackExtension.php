<?php

/**
 * This file is part of blackforest/symfony-favicons-webpack-bundle.
 *
 * (c) 2014-2021 The Blackforest team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    blackforest/symfony-favicons-webpack-bundle
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2014-2021 The Blackforest team.
 * @license    https://github.com/blackforest/symfony-favicons-webpack-bundle/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace BlackForest\Symfony\WebpackEncoreBundle\DependencyInjection;

use BlackForest\Symfony\WebpackEncoreBundle\Asset\Favicons;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * The favicons webpack extension.
 */
final class FaviconsWebpackExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $this->configureFavicons($config, $container);
    }

    /**
     * Configure favicons.
     *
     * @param array            $config    The bundle configuration.
     * @param ContainerBuilder $container The container builder.
     *
     * @return void
     */
    private function configureFavicons(array $config, ContainerBuilder $container): void
    {
        if (!isset($config['favicons']) || empty($config['favicons'])) {
            return;
        }

        $factories = [];
        $cacheKeys = [];
        foreach ($config['favicons'] as $name => $path) {
            $factories[$name] = $this->faviconsFactory($container, $config['cache'], $name, $path);
            $cacheKeys[$name] = $path;
        }
    }

    /**
     * Create the favicons definition.
     *
     * @param ContainerBuilder $container The container builder.
     * @param bool             $useCache  Determine for use the cache.
     * @param string           $name      The favicon name.
     * @param string           $path      The favicon path.
     *
     * @return Reference
     */
    private function faviconsFactory(ContainerBuilder $container, bool $useCache, string $name, string $path): Reference
    {
        $id        = \sprintf('%s[%s]', Favicons::class, $name);
        $arguments = [
            '$faviconHtmlPath' => $path,
            '$cacheKey'        => $name,
            '$fileLocator'     => new Reference('file_locator'),
            '$cache'           => $useCache ? new Reference('favicons_webpack.cache') : null
        ];

        $definition = new Definition(Favicons::class, $arguments);
        $definition->addTag('kernel.reset', ['method' => 'reset']);
        $container->setDefinition($id, $definition);

        return new Reference($id);
    }
}
