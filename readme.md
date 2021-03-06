## Helloprint Challenge - Backend
### Scope
STACK REQUIREMENTS
- PHP 7.4
- Kafka 2.12
- Postgres 10
- Docker v 3.7

No usage of any kind of framework for this challenge.

GOALS
- Build a PHP script called "Requester" that sends a message "Hi, " to the "Broker" service.
- The "Broker" must store this message on the "Broker" schema in Postgres ("request" table), and
return a token that identifies this registration to the "Requester".
- After the "Broker" will produce a message into Kafka "Topic A".
- The "Requester" must start pulling a response from the "Broker" into intervals of 50ms, at the
maximum of 1s. If until then doesn't get a response, throws an exception "no response", and if
does prints the final message.
- The "Topic A" will be consumed by the "Service A", that will append a random name to the
"Requester" message. List of names: "Joao, Bram, Gabriel, Fehim, Eni, Patrick, Micha, Mirzet, Liliana,
Sebastien".
- After "Service A" appends the name will send this message to the "Broker" service.
- Then the "Broker" will grab this message will produce a message into Kafka "Topic B".
- The "Topic B" will be read by the "Service B", that will append the word "Bye". This full message will
be store directly on the "Broker" schema "request" table under the token registration to be fetched
by the broker later on as a response to the “Requester”.


## Solution
Minimal implementation to demonstrate the basic concepts of working with microservices with asynchronous
events.

### How to run and test project
- Step1: Change the value KAFKA_ADVERTISED_HOST_NAME inside to docker-compose.yml to your host IP.
- Step2: ./setup.sh - build the project. Press CRTL+C once done.
- Step3: ./test.sh - to run the PHPUnit
- Step4: ./run.sh - to run the positive flow
- Step5: docker-compose stop service_b - to test the timeout flow
        ./run.sh - the Requester.php script will exit after 1s of waiting for response from broker.


- Incase of problem - remove vendor and install composer again 
    - `docker-composer exec broker rm -fr vendor`
    - `docker-composer exec broker composer install`
    
- Below link have a video where I run all the above 5 steps
  https://www.loom.com/share/caa312f1ac124460b872979cc8ad3399
    
### Understanding Requester flow
The below code currently executing inside the src/Requester.php
```
$client = new RestClient(new CurlRequest());

$message = 'Hi';
$response = $client->post('http://localhost:8000/message', ["message" => $message] );
if(!$response['data']) {
    die('no token found');
}

echo "Fetching message for token:".$response['data']." \n";
$time = 0;
while(1) {
    echo "..";
    $result = $client->get('http://localhost:8000/token?token='.$response['data']);
    if(!empty($result["data"]) && $message != $result["data"]) {
        var_dump($result);
        break;
    }
    usleep(50000);
    $time += 50000;
    if($time > 1000000) {
        echo "\n Timeout!! No response got from the broker..\n";
        break;
    }
}
```

The input is hardcoded right now, if you wanted to test with other string, 
you need to update this file and run the ./run.sh file again. 
Currently, the Requester.php wil send a message "Hi" to Broker service which is running at http://localhost:8000.
(I am using php inbuilt server)

The broker service will store the data in database table `requests`. And then publish the message to kafka on `topicA`.
Service A which is running in loop, will pick the message and attach a random person name and push to kafka on `topicB`.
The message pushed by serviceA would be something like "Hi John".

The Service B which is also running in loop, will pick the message and add "Bye!!" at the end of message 
and store in database inside the requests table. The end message would now look something like "Hi John Bye!!"
 
Our Requster.php script will keep polling the broker service based on token received at the interval of 50ms.
If the Requester get the message before 1s , it will var_dump the response on console and exit.
If the Requester didnt get the message by 1s, it will exit the loop with message "Timeout!! No response".
