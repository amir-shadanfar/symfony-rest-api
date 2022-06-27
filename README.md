# Symfony restfull api
![using postman to test api ](http://g.recordit.co/Hs3hh0ohy8.gif)
ABC Company is a supplier company and has 3 customers. These customers have their own usernames and passwords. Customers can create and view orders using Restful services. In line with these expectations, we expect you to prepare the services listed below:

Requested services:
- Login to the system and get JWT Token
- Creating a new order (orderCode, productid, quantity, address, shippingDate)
- Update the order (if the shippingDate has not arrived yet)
- See order detail
- List all your orders

Things to earn plus points:
- Making Docker image (LEMP)
- Postman Collection or Swagger-ui
- Unit Tests
- readme

## Installation
This project is running on Docker, so after cloning it, you should run this command to start server :

    docker-compose up -d
 
So, according to project requirement I used Symfony 4.4 to coding. In order to import database and its seeder, you should run these commands :

    bin/console doctrine:migration:migrate
    bin/console doctrine:fixtures:load

![enter image description here](http://g.recordit.co/HePmZJhgV7.gif)

Finally you could download exported postman collection from this [link](https://raw.githubusercontent.com/amir-shadanfar/symfony-rest-api/master/Symfony-restfull.postman_collection.json).
