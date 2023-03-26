## Jitera UserConnect System
This project aims to create a Laravel backend that manages a user connect system. The system 
will allow users to be stored in a database and accessed via a RESTful API. Specifically, 
the project requires the creation of a "users" table that has the necessary fields to store 
the user data, including their name, email, address, phone, website, and company. The system 
should also provide API endpoints for users to follow and unfollow other users, as well as 
search for followers by name.

## Can be improved
- Adding some more unit and feature tests.
- Add more error handling
- Can be added repository pattern. Though I don't use in this system as this will overkill for this simple system\
- Dockerized the project for easy deployment
- CI/CD pipeline can be added for the project

## Please be noted
- I have used JWT for authentication. So, you need to pass the token in the header of the request. You can get the token from the login endpoint.
- If you use the postman collection the token will be automatically added to the header of the request. You can find the postman collection in the API Docs section.
- Used service layer design pattern to make the code more clean and reusable.
- Unit and feature tests are added for the API endpoints and Service classes.

## Getting Started
- Clone the repository to your local machine.
- Run `composer install` to install the project dependencies.
- Run `php artisan key:generate` to generate the application key.
- Create a .env file based on the .env.example file and set your database credentials.
- Run `php artisan migrate`to create the necessary tables in the database.
- Run `php artisan db:seed` to seed the database with sample data (optional).
  - You can use the following credentials to login:
    - email: `nayeemdev@yahoo.com `
    - password: `Secret123@`
- Run `php artisan jwt:secret` to generate the JWT secret key.
- Run `php artisan serve` to start the development server. and in the browser go to `http://localhost:8000/` You can see the docs

## Live Server
The project is deployed on the live server. URL is [http://userconnect.rufostudio.com/](http://userconnect.rufostudio.com/)

## API Docs
Here is the link of postman collection for this project:
[https://documenter.getpostman.com/view/25794553/2s93RNzFaE](https://documenter.getpostman.com/view/25794553/2s93RNzFaE)

You can test the API endpoints using the postman collection. Please make sure you have select
the live server environment from the top right corner.

## Unit/Feature Tests
This project includes unit and feature tests for the API endpoints and Service classes. You 
can run the tests by running the following command:
```
php artisan test
```
Here is the image of the test results:

![TestCase](https://user-images.githubusercontent.com/40033062/227783541-48bb9ad4-d49f-4066-ae82-c1f7e8d0afdc.png)

## Database Architecture
Here's a description of the database architecture used in this project:

![DBDesign](https://user-images.githubusercontent.com/40033062/227783566-a2bc4023-d245-45cc-a745-0370ba135c2c.png)


## Conclusion
Thank you for reviewing this documentation. If you have any questions or concerns, please don't 
hesitate to contact me at `nayeem9812@gmail.com`

