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
use Doctrine\DBAL\Connection;
use NetGroup\UserGuide\Classes\Contao\Enums\RendererUrlPart;
use NetGroup\UserGuide\Classes\Contao\Enums\TableNames;

class TlGuides
{


    public function __construct(private readonly Connection $connection)
    {

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
    public function loadKategories(DataContainer $dc): array
    {
        $options    = [];
        $pid        = $dc->currentPid;
        $query      = $this->connection->createQueryBuilder();
        $rows       = $query->select('*')
                            ->from(TableNames::tl_manual_categories->name)
                            ->where('pid = :pid', $pid)
                            ->setParameter('pid', $pid)
                            ->executeQuery()
                            ->fetchAllAssociative();

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
     * @param array $row
     * @param string $label
     * @param DataContainer $dc
     * @param array $labels
     *
     * @return string
     */
    public function createLabel(array $row, string $label, DataContainer $dc, array $labels): string
    {
        $request = System::getContainer()->get('request_stack')?->getCurrentRequest();

        if (null !== $request) {
            $icon   = !empty($row['icon']) ? $row['icon'] : 'fa-solid fa-circle-info';
            $url    = $request->getUri();
            $link   = $url . '&key=' . RendererUrlPart::key->value;
            $link  .= '&' . RendererUrlPart::guideId->value . '=' . $row['id'];
            $img    = '<span style="font-size: 1.2em; color: #313132;">';
            $img   .= '<i class="' . $icon. '"></i></span>';
            $label  = $img . '<a href="' . $link . '" style="padding-left: 5px;">' . $label . '</a>';
        }

        return $label;
    }


    /**
     * Fügt font awesome hinzu.
     *
     * @return void
     */
    public function addFontAwesome(): void
    {
        $GLOBALS['TL_CSS'][] = 'bundles/netgroupuserguide/icons/fontawesome-free-6.6.0-web/css/all.css';
    }
}
