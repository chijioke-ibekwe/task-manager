# Task Management System
RESTful APIs for a task management system built on Laravel 11

## Getting Started
### Prerequisites
To make it easy to run this application, without the headache of dependencies, this project is set up for containerization. Hence, the only real prerequisite you need is **Docker**. However, these are some of the dependencies:
- PHP v8.2
- Laravel v11
- Postgres
- SQLite

## How to Run
To run this project:
1. Clone the repository using the following command:
   ```
   git clone https://github.com/chijioke-ibekwe/task-manager.git
   ```

2. A docker compose file, a Dockerfile and other config files are attached to this project to help you containerize the entire          
   application. Hence, you just need to run the command below to start the application.
   ```
   docker compose up -d
   ```
   This command will spin-up the following containers running in a detached mode:
    = The task manager laravel application running on `localhost:8000`
    - A postgres RDBMS running on `localhost:5433`, and
    - A pgAdmin UI running on `localhost:5051` for managing the database.

   The task manager container might take a while to fully initialize. You can monitor its progress using the command:
   ```
   docker logs --follow task_manager
   ```

3. After the containers are fully initialized, you should see the test `` when you visit `localhost:8000` on your browser.

4. The Postman Collection link for the project can be found on https://documenter.getpostman.com/view/12633695/2sA3kYizut
   It contains detailed documentation on how to use the endpoints.

5. To run tests when running the application locally, run
   ```
   php artisan test
   ```
   NB: You need sqlite driver installed to run this successfully
