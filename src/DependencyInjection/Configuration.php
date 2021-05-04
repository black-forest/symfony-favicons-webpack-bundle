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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The bundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('favicons_webpack');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = \method_exists($treeBuilder, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('webpack_encore');

        $rootNode
            ->children()
                ->arrayNode('favicons')
                    ->info('Configure your generated favicons with the favicons webpack plugin.')
                    ->example('{ app: "@AppBundle/Resources/public/favicon.html" }')
                    ->normalizeKeys(false)
                    ->scalarPrototype()
                        ->validate()
                            ->ifEmpty()
                            ->thenUnset()
                        ->end()
                    ->end()
                    ->booleanNode('cache')
                        ->info('Enable caching of the favicons file(s).')
                        ->defaultFalse()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
