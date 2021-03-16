## Helloprint Challenge - Backend
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
    
- Link of video for check the whole Flow
  https://www.loom.com/share/caa312f1ac124460b872979cc8ad3399
    
