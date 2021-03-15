<?php

namespace Helloprint\Database;

use Helloprint\Exceptions\ModelException;

/**
 * Class DbConnection
 * @package Helloprint\Database
 */
class DbConnection
{
    protected ?\PDO $db;

    protected \PDOStatement $query;

    /**
     * DbConnection constructor.
     */
    public function __construct() {

        $this->db = new \PDO(
            "pgsql:host=".config('db.host').
            ";port=".config('db.port').
            ";dbname=".config('db.dbname').
            ";user=".config('db.user').
            ";password=".config('db.password').";");

        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute Query
     *
     * @param string $sql
     * @param array $bindParam
     * @return bool|mixed
     * @throws ModelException
     */
    public function execStatement(string $sql, array $bindParam)
    {
        try{
            $this->query = $this->db->prepare($sql);
            return $this->query->execute($bindParam);
        }catch(\Exception $e) {
            throw new ModelException($e->getMessage());
        }
    }
}
