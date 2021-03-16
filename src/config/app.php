<?php
return
    [
        "db" => [
            "host" => getenv('DATABASE_HOST'),
            "port" => getenv('DATABASE_PORT'),
            "dbname" => getenv('DATABASE_NAME'),
            "user" => getenv('DATABASE_USER'),
            "password" => getenv('DATABASE_PASSWORD'),
        ],
        "kafka" => [
            "host" => getenv('KAFKA_HOST'),
        ],
        "consumer" => [
            "group" => "helloprint"
        ]
    ];
