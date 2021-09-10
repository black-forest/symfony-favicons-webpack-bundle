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

use Symfony\Contracts\Service\ResetInterface;

/**
 * The tag renderer.
 */
final class TagRenderer implements ResetInterface
{
    /**
     * The favicon collection.
     *
     * @var FaviconsCollectionInterface
     */
    private $faviconsCollection;

    /**
     * The rendered favicons.
     *
     * @var array|null
     */
    private $renderedFavicons;

    /**
     * The constructor.
     *
     * @param FaviconsCollectionInterface $faviconsCollection The favicon collection.
     */
    public function __construct(FaviconsCollectionInterface $faviconsCollection)
    {
        $this->faviconsCollection = $faviconsCollection;
    }

    public function renderFavicons(string $faviconName): string
    {

    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $this->renderedFavicons = null;
    }
}
