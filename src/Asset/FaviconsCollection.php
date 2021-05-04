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

namespace BlackForest\Symfony\WebpackEncoreBundle\Asset;

use BlackForest\Symfony\WebpackEncoreBundle\Exception\FaviconsNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Aggregate the different favicons configured in the container.
 *
 * Retrieve the Favicons instance from the given key.
 */
final class FaviconsCollection implements FaviconsCollectionInterface
{
    /**
     * The favicons collection.
     *
     * @var ContainerInterface
     */
    private $faviconsCollection;

    /**
     * The constructor.
     *
     * @param ContainerInterface $faviconsCollection The favicons collection.
     */
    public function __construct(ContainerInterface $faviconsCollection)
    {
        $this->faviconsCollection = $faviconsCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @throws FaviconsNotFoundException if the favicons does not exist.
     */
    public function getFaviconsCollection(string $faviconName): FaviconsInterface
    {
        if (!$this->faviconsCollection->has($faviconName)) {
            throw new FaviconsNotFoundException(\sprintf('The favicons "%s" is not configured', $faviconName));
        }

        return $this->faviconsCollection->get($faviconName);
    }
}
