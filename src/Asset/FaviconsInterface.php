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
 * The favicons interface.
 */
interface FaviconsInterface extends ResetInterface
{
    /**
     * Get the favicon html head tags.
     *
     * @return String[]|null
     */
    public function getFaviconHtmlHeadTags(): ?array;
}
