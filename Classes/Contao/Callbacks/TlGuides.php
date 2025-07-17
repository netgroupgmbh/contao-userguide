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
use Doctrine\DBAL\Connection;

class TlGuides
{


    public function __construct(private readonly Connection $connection)
    {

    }


    /**
     * Lädt die Kategorien des Hanbuchs.
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
        $table      = 'tl_manual_categories';
        $query      = $this->connection->createQueryBuilder();
        $rows       = $query->select('*')
                            ->from($table)
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
}
