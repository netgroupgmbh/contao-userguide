<?php

/**
 * @since       21.07.2025 - 13:03
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use NetGroup\UserGuide\Classes\Services\Factories\FinderFactory;

class FontAewsomeHelper
{


    public const ICON_FOLDER = '/public/bundles/netgroupuserguide/icons/fontawesome-free-6.6.0-web/svgs';


    /**
     * @param string        $projectRoot
     * @param FinderFactory $finderFactory
     */
    public function __construct(
        private readonly string $projectRoot,
        private readonly FinderFactory $finderFactory
    ) {
    }


    /**
     * Erstellt aus den SVGs die Optionen für die Icons.
     *
     * @return string[]
     */
    public function createOpteions(): array
    {
        $finder     = $this->finderFactory->createFinder();
        $fs         = $this->finderFactory->createFileSystem();
        $options    = [];

        if (true === $fs->exists($this->projectRoot . self::ICON_FOLDER)) {
            $finder->files()->in($this->projectRoot . self::ICON_FOLDER . '/*/')->name('*.svg');

            if (true === $finder->hasResults()) {
                foreach ($finder as $file) {
                    $label              = \basename($file->getPath()) . ': ' . $file->getBasename('.svg');
                    $value              = 'fa-' . \basename($file->getPath()) . ' fa-' .$file->getBasename('.svg');
                    $options[$value]    = $label;
                }
            }
        }

        return $options;
    }
}
