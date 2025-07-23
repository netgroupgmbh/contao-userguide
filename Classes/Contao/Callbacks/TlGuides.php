<?php

/**
 * @since       17.07.2025 - 10:25
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Contao\Callbacks;

use Contao\DataContainer;
use Contao\System;
use NetGroup\UserGuide\Classes\Contao\Enums\RendererUrlPart;
use NetGroup\UserGuide\Classes\Contao\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\AssetHelper;
use NetGroup\UserGuide\Classes\Services\Helper\FontAewsomeHelper;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;

class TlGuides
{


    /**
     * @param FontAewsomeHelper $fontAewsomeHelper
     * @param AssetHelper       $assetHelper
     * @param QueryHelper       $queryHelper
     * @param LockHelper        $lockHelper
     */
    public function __construct(
        private readonly FontAewsomeHelper $fontAewsomeHelper,
        private readonly AssetHelper $assetHelper,
        private readonly QueryHelper $queryHelper,
        private readonly LockHelper $lockHelper
    ) {
    }


    /**
     * options_callback: Lädt die Kategorien des Hanbuchs.
     *
     * @param DataContainer $dc
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadCategories(DataContainer $dc): array
    {
        $options    = [];
        $pid        = $dc->currentPid;
        $rows       = $this->queryHelper->loadCategoriesFromPid($pid);

        foreach ($rows as $row) {
            if (!empty($row['id']) && !empty($row['title'])) {
                $options[$row['id']] = $row['title'];
            }
        }

        return $options;
    }


    /**
     * label_callback: Erstellt die Links für die Anischt der Anleitungen.
     *
     * @param array         $row
     * @param string        $label
     * @param DataContainer $dc
     * @param array         $labels
     *
     * @return string
     */
    public function createLabel(array $row, string $label, DataContainer $dc, array $labels): string
    {
        $request = System::getContainer()->get('request_stack')?->getCurrentRequest();

        if (null !== $request) {
            $icon       = !empty($row['icon']) ? $row['icon'] : 'fa-solid fa-circle-info';
            $url        = $request->getUri();
            $link       = $url . '&key=' . RendererUrlPart::key->value;
            $link      .= '&' . RendererUrlPart::guideId->value . '=' . $row['id'];
            $img        = '<span style="font-size: 1.2em; color: #313132;">';
            $img       .= '<i class="' . $icon. '"></i></span>';
            $newLabel   = '<a href="' . $link . '">' . $img;
            $newLabel  .= '<span  style="padding-left: 5px;">' . $label . '</span></a>';
            $label      = $newLabel;
        }

        return $label;
    }


    /**
     * onload_callback: Fügt die Assets hinzu.
     *
     * @param DataContainer|null $dc
     *
     * @return void
     */
    public function addAssets(?DataContainer $dc): void
    {
        $this->assetHelper->incldueCss();
        $this->assetHelper->includeJavaScript();
    }


    /**
     * onload_callback: Prüft die Berechtigung einen Abschnitt hinzuzufügen.
     *
     * @param DataContainer|null $dc
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function checkPermissions(?DataContainer $dc): void
    {
        $table = TableNames::tl_guides->name;
        if (null !== $dc && $table === $dc->table) {
            $pid = $dc->currentPid;

            if (true === $this->lockHelper->checkLocked($pid, TableNames::tl_manuals)) { // check is tl_manual locked
                $GLOBALS['TL_DCA'][$table]['list']['global_operations'] = [];
                $GLOBALS['TL_DCA'][$table]['config']['closed']          = true;
                $GLOBALS['TL_DCA'][$table]['config']['notDeletable']    = true;
                $GLOBALS['TL_DCA'][$table]['config']['notEditable']     = true;
                $GLOBALS['TL_DCA'][$table]['config']['notCopyable']     = true;
            }
        }
    }


    /**
     * options_callback: Erstellt das Array mit den Optionen für das Icon-Feld.
     *
     * @param DataContainer|null $dc
     *
     * @return array
     */
    public function generateFontAwesomeOptions(?DataContainer $dc): array
    {
        return $this->fontAewsomeHelper->createOpteions();
    }
}
