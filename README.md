# Symfony restfull api
![using postman to test api ](http://g.recordit.co/Hs3hh0ohy8.gif)
ABC Company tedarikçi bir firmadır ve 3 müşterisi vardır. Bu müşterilerin kendilerine ait kullanıcı adları ve şifreleri vardır.  Müşteriler Restful servislerini kullanarak sipariş oluşturabilir ve görebilirler. Bu beklentiler doğrultusunda  aşağıda listelenen servisleri hazırlamanı bekliyoruz.

İstenen servisler:
-   Sisteme login olma ve JWT Token alma
-   Yeni sipariş oluşturma (orderCode, productid, quantity, address, shippingDate)
-   Siparişi güncelleme (shippingDate henüz gelmediyse)
-   Sipariş detayını görme
-   Tüm siparişlerini listeleme

Artı puan kazandıracak şeyler:
-   Docker image ın yapılması (LEMP)
-   Postman Collection  veya Swagger-ui
-   Unit Tests
-   Readme

## Installation
This project is running on Docker, so after cloning it, you should run this command to start server :

    docker-compose up -d
  ![enter image description here](http://g.recordit.co/fXtpAHgyAf.gif)
So, according to project requirement I used Symfony 4.4 to coding. In order to import database and its seeder, you should run these commands :

    bin/console doctrine:migration:migrate
    bin/console doctrine:fixtures:load

Finally you could download exported postman collection from this [link](https://raw.githubusercontent.com/amir-shadanfar/symfony-rest-api/master/Symfony-restfull.postman_collection.json).
