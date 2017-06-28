# Shareable Innovations gateway to Flow.cl

## Local setup

1. You need to have redis installed(you can also use a third party redis service) and configure the connection in `src/settings.php`.
2. You should be able to run the project with `php -S localhost:80 -t public public/index.php`
3. Done!

## Docker Setup

1. Install docker engine and docker compose(it depends on the OS you are using).
2. Go inside the `docker/` folder.
3. Run `docker-compose up -d` 
4. Done.


**Note**: Depending on your environment you may need to change the port numbers.
