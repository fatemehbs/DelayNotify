# Daily Notify


# Project Setup

Make sure you have Laravel installed. If not, visit the Laravel website for installation instructions.

Clone your project repository or navigate to the project directory.

Install project dependencies by running the following command in your terminal:

# Run locally

1. Install the required php packages

```bash
composer install
```

2. install `mysql`
3. Create a .env file by duplicating the .env.example file:
```bash
   cp .env.example .env
```

4. configure the database connection settings in the .env file according to your MySQL setup:

```bash
DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=your_database_port
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
5. Run the database migrations to create the required tables:

```bash
php artisan migrate
```

6.Seed the tables with initial data by running the following command:

```bash
php artisan db:seed
```
6. This will populate your tables with sample data using the seeders you have created.

Start the development server:
```bash
php artisan serve
```

You have successfully set up your "DelayNotify" project with Laravel and MySQL. Feel free to explore the application


# Api


Sure! Here are the APIs for your "DelayNotify" project:

1. Assign Agent to Order API:

``` 
Endpoint: http://127.0.0.1:8000/api/administration/assign_agent
Method: POST
Parameters:
agent_id: The ID of the agent to be assigned to the delay report order.
Description: This API is used to assign an agent to a delay report order for further processing.
```
2. Vendors Delay Report API:

``` 
Endpoint: http://127.0.0.1:8000/api/administration/vendors_delay_report
Method: GET
Description: This API retrieves a report of delays for all vendors
```

3. Report Delay API:

```
Endpoint: http://127.0.0.1:8000/api/v1.0/report_delay/{order_id}
Method: POST
Parameters:
order_id: The ID of the order for which the delay report is being submitted.
Description: This API is used to submit a delay report for a specific order. The order_id parameter identifies the order for which the delay report is being submitted.
```


# Test

```angular2html
php artisan test
```

# docker
```bash
docker build -t dailynotify .

docker run --rm dailynotify php artisan migrate
```
