<?php

namespace Helloprint\Tests;

use Helloprint\Database\DbConnection;
use Helloprint\Exceptions\ModelException;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    /**
     * @throws ModelException
     */
    private function setUpDatabase()
    {
        putenv('DATABASE_HOST=postgresdb');
        putenv('DATABASE_PORT=5432');
        putenv('DATABASE_NAME=tests');
        putenv('DATABASE_USER=root');
        putenv('DATABASE_PASSWORD=root');


        $conn = new DbConnection();
        $conn->execStatement('TRUNCATE requests',[]);
    }
}
