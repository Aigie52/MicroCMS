<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 15/03/2017
 * Time: 16:12
 */

namespace MicroCMS\DAO;


use Doctrine\DBAL\Connection;

abstract class DAO
{
    /**
     * Database connection
     * @var Connection
     */
    private $db;

    /**
     * DAO constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Grants access to the database connection object
     * @return Connection
     */
    protected function getDb()
    {
        return $this->db;
    }

    /**
     * Builds a domain object from a db row
     * Must be overriden by child classes
     * @param array $row
     */
    protected abstract function buildDomainObject(array $row);
}
