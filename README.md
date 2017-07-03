# Shareable Innovations gateway to Flow.cl

## Local setup & Run

1. In order to run locally, you need to create a `.env` file and add the key-value pairs as in the `.env.example` file. There you will find app, flow and redis configuration values
2. You should be able to run the project with `php -S localhost:80 -t public public/index.php`
3. Done!

## Docker Setup & Run
1. In order to run locally, you need to create a `.env` file and add the key-value pairs as in the `.env.example` file. There you will find app, flow and redis configuration values.
2. Install docker engine and docker compose(it depends on the OS you are using). **Important:** Keep in mind that there is already a docker container for Redis(see `docker-compose.yml`) so you should use that one.   
3. Go inside the `docker/` folder.
4. Run `docker-compose up -d` 
5. Done.


**Deploy & Testing**: 
1. Depending on your environment you may need to change the **port numbers**.
2. For a **testing** environment, you can create a `.env.testing` file which will override production and local environments configuration.
3. For **production** environment, you should remove both your `.env` or `.env.example` files as the app will take configuration from the values inside the `config/app.php` file.
