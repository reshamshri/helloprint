<?php


namespace Helloprint\Tests\Unit;


use Helloprint\Kafka\Consumer;
use Helloprint\Kafka\Producer;
use Helloprint\Tests\BaseTest;

class HelperTest extends BaseTest
{

    /** @test */
    public function can_fetch_env_variables()
    {
        putenv('DATABASE_NAME=tests');
        $result = config("db.dbname");
        $this->assertSame("tests",$result);
    }
    /** @test */
    public function can_generate_token()
    {
        $this->assertNotNull(getToken());
    }

    /** @test  */
    public function get_consumer_instance()
    {
        putenv('KAFKA_HOST=localhost');
        $consumer = consumer();
        $this->assertInstanceOf(Consumer::class, $consumer);

    }

    /** @test  */
    public function get_producer_instance(){
        putenv('KAFKA_HOST=localhost');
        $producer = producer();
        $this->assertInstanceOf(Producer::class, $producer);

    }

}
