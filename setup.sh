echo -e "Preparing Build\n"
docker-compose build
echo -e "Starting Containers\n"
docker-compose up -d
echo -e "Running composer install. Once you see development started , press\n"
docker-compose logs -f broker
echo -e "Container Started, press CTRL+C, Now you can run ./run.sh \n"