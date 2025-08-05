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
use NetGroup\UserGuide\Classes\Enums\TableNames;

class QueryHelper
{


    /**
     * @param Connection $connection
     */
    public function __construct(private readonly Connection $connection)
    {
    }


    /**
     * Lädt die Kategorien eines Handbuchs.
     *
     * @param int $pid
     *
     * @return mixed[][]
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadCategoriesFromPid(int $pid): array
    {
        $query = $this->connection->createQueryBuilder();

        return $query->select('*')
                     ->from(TableNames::tl_manual_categories->name)
                     ->where('pid = :pid', $pid)
                     ->setParameter('pid', $pid)
                     ->executeQuery()
                     ->fetchAllAssociative();
    }


    /**
     * Lädt den Wert eines Felds einer Anleitung.
     *
     * @param int        $id
     * @param string     $field
     * @param TableNames $table
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadFieldFromTable(int $id, string $field, TableNames $table): string
    {
        $query  = $this->connection->createQueryBuilder();
        $row    = $query->select($field)
                        ->from($table->name)
                        ->where('id = :id')
                        ->setParameter('id', $id)
                        ->executeQuery()
                        ->fetchFirstColumn();

        return !empty($row) ? (string) \array_shift($row) : '';
    }


    /**
     * Lädt den Inhalt einer Anleitung
     *
     * @param int $id
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadContentFromGuide(int $id): string
    {
        return $this->loadFieldFromTable($id, 'content', TableNames::tl_guides);
    }


    /**
     * Lädt die Pid einer Anleitung oder einer Kategorie.
     *
     * @param int $id
     * @param     $table
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadPidFromGuide(int $id, TableNames $table): string
    {
        return $this->loadFieldFromTable($id, 'pid', $table);
    }


    /**
     * Läde den Lock-Stauts einer Anleitung.
     *
     * @param int        $id
     * @param TableNames $table
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadLocked(int $id, TableNames $table): bool
    {
        return (bool) $this->loadFieldFromTable($id, 'locked', $table);
    }
}
