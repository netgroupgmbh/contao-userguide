<?php

/**
 * @since       17.07.2025 - 13:00
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use Doctrine\DBAL\Connection;
use NetGroup\UserGuide\Classes\Contao\Enums\TableNames;

class QueryHelper
{


    public function __construct(private readonly Connection $connection)
    {
    }

    public function loadContentFromGuide(int $id): string
    {
        $query  = $this->connection->createQueryBuilder();
        $row    = $query->select('content')
                        ->from(TableNames::tl_guides->name)
                        ->where('id = :id')
                        ->setParameter('id', $id)
                        ->executeQuery()
                        ->fetchFirstColumn();

        return !empty($row) ? (string) \array_shift($row) : '';
    }
}
