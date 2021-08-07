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
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Returns the favicon tags for the html head.
 * This reads the html file, that where generated with webpack.
 */
final class Favicons implements FaviconsInterface
{
    /**
     * The favicon html path.
     *
     * @var string
     */
    private $faviconHtmlPath;

    /**
     * The cache key.
     *
     * @var string
     */
    private $cacheKey;

    /**
     * The file locator.
     *
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * The cache.
     *
     * @var CacheItemPoolInterface|null
     */
    private $cache;

    /**
     * The favicons data.
     *
     * @var array|null
     */
    private $faviconsData;

    /**
     * The constructor.
     *
     * @param string                      $faviconHtmlPath The favicon html path.
     * @param string                      $cacheKey        The cache key.
     * @param FileLocatorInterface        $fileLocator     The file locator.
     * @param CacheItemPoolInterface|null $cache           The cache.
     */
    public function __construct(
        string $faviconHtmlPath,
        string $cacheKey,
        FileLocatorInterface $fileLocator,
        CacheItemPoolInterface $cache = null
    ) {
        $this->faviconHtmlPath = $faviconHtmlPath;
        $this->cacheKey        = $cacheKey;
        $this->fileLocator     = $fileLocator;
        $this->cache           = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function getFaviconHtmlHeadTags(): ?array
    {
        $this->validateFavicons();

        return $this->faviconsData;
    }

    /**
     * Resets the state of this service.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->faviconsData = null;
    }

    /**
     * Validate the favicons.
     *
     * @return void
     *
     * @throws FaviconsNotFoundException It throws, if the favicons file not flound.
     */
    private function validateFavicons(): void
    {
        if (null !== $this->faviconsData) {
            return;
        }

        if ($this->cache) {
            $cached = $this->cache->getItem($this->cacheKey);

            if ($cached->isHit()) {
                $this->faviconsData = $cached->get();

                return;
            }
        }

        if (!\file_exists($path = $this->fileLocator->locate($this->faviconHtmlPath))) {
            throw new FaviconsNotFoundException(
                \sprintf(
                    'Could not find the favicons file: the file "%s" does not exist.',
                    $this->faviconHtmlPath
                )
            );
        }

        $content = \preg_replace('/></', ">\n<", \file_get_contents($path));
        \preg_match_all('#<(link|meta)[^>]*(.*?)>$#m', $content, $matches);

        foreach ($matches[0] as $match) {
            $this->faviconsData[] = \preg_replace('/\s\s+/', ' ', $match);
        }

        if ($this->cache) {
            $this->cache->save($cached->set($this->faviconsData));
        }
    }
}
