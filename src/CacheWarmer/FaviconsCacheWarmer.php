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

namespace BlackForest\Symfony\WebpackEncoreBundle\CacheWarmer;


use BlackForest\Symfony\WebpackEncoreBundle\Asset\Favicons;
use BlackForest\Symfony\WebpackEncoreBundle\Exception\FaviconsNotFoundException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\AbstractPhpFileCacheWarmer;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * The cache warmer for the favicons.
 */
final class FaviconsCacheWarmer extends AbstractPhpFileCacheWarmer
{
    /**
     * The cache keys.
     *
     * @var array
     */
    private $cacheKeys;

    /**
     * The file locator.
     *
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * The constructor.
     *
     * @param array                  $cacheKeys     The cache keys.
     * @param FileLocatorInterface   $fileLocator   The file locator.
     * @param string                 $phpArrayFile  The php file array.
     * @param CacheItemPoolInterface $fallbackCache The fallback cache.
     */
    public function __construct(
        array $cacheKeys,
        FileLocatorInterface $fileLocator,
        string $phpArrayFile,
        CacheItemPoolInterface $fallbackCache
    ) {
        $this->cacheKeys   = $cacheKeys;
        $this->fileLocator = $fileLocator;

        parent::__construct($phpArrayFile, $fallbackCache);
    }

    /**
     * {@inheritDoc}
     */
    protected function doWarmUp($cacheDir, ArrayAdapter $arrayAdapter): bool
    {
        foreach ($this->cacheKeys as $cacheKey => $faviconHtmlPath) {
            // If the file does not exist then just skip past this favicons.
            if (!\file_exists($this->fileLocator->locate($faviconHtmlPath))) {
                continue;
            }

            $favicons = new Favicons($faviconHtmlPath, $cacheKey, $this->fileLocator, $arrayAdapter);

            try {
                $favicons->getFaviconHtmlHeadTags();
            } catch (FaviconsNotFoundException $e) {
                // ignore exception.
            }
        }

        return true;
    }
}
