You can try out the sample application [here](http://147.182.130.127/).

**IMPORTANT NOTES**
Unfortunately, there can be some permission problems on certain systems if you try running the e-commerce project. Mainly on Windows and WSL. If you run into issues, try to run `sudo docker-compose up`.

If there are still problems, try to modify the permissions of these files:
- `chmod +x ./wait-for-it.sh`
- `chmod +x ./artisan`
- `chmod 0777 -R ./storage`

This should solve the problems in most cases. These issues depend on your exact system and users, so unfortunately they're somewhat out of my control.

If you're still having problems, please check out the source from this repository and try again: [https://gitlab.com/joomartin/e-commerce](https://gitlab.com/joomartin/e-commerce)

One way to get rid of these platform dependent problems is to push Docker images to an image registry and use these instead of building images on your local machine. It works in a company environment, however, Docker Hub restricts the number of image pulls so I can't be sure it works for every one of you.

**General**
Thank you for purchasing "Microservices with Laravel"

In the "Ecommerce Source Code" folder you'll find the source code of the e-commerce project built in the book.
This is a simple monolith repo. It's fine for solo projects, or maybe small teams but it can be a poor choice for bigger projects.

Here you can find a multi-repo of the same project: https://gitlab.com/microservices-with-laravel/e-commerce
In this repo, I'm using gitmodules so it has a main repository. This is the one you can install on servers when you're deploying a real project: https://gitlab.com/microservices-with-laravel/e-commerce/e-commerce

The "Ecommerce Common Package Source Code" folder contains the source of a composer packages used by every service.
You can also find the source here: https://gitlab.com/microservices-with-laravel/e-commerce/common

**Please note that the project on Gitlab has the same services and overall architecture as the one in the ZIP file. The implementation of each service is different though.

**Install:**
1. Unzip the source code
2. `docker-compose up`
3. `curl localhost:8000/api/v1/catalog`

**Other commands:**
- Seed databases: `./bin/run-seed.sh`
- Reset databases: `./bin/reset-db.sh`
- Reset Redis:
```
docker-compose exec redis redis-cli
127.0.0.1:6379> FLUSHALL
```
- Import `api-endpoints-postman.json` into Postman to see all APIs

**Services:**
- Frontend: [http://localhost:3000](http://localhost:3000)
- Proxy: [http://localhost:8000](http://localhost:8000)
- Products API: [http://localhost:8001](http://localhost:8001)
- Ratings API: [http://localhost:8002](http://localhost:8002)
- Warehouse API: [http://localhost:8003](http://localhost:8003)
- Catalog API: [http://localhost:8004](http://localhost:8004) 
- Orders API: [http://localhost:8005](http://localhost:8005)
- MySQL: [http://localhost:33060](http://localhost:33060)
- Redis: [http://localhost:63790](http://localhost:63790)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)
- Redis commander: [http://localhost:63791](http://localhost:63791)
- You can change everything in the `docker-compose.yml`

**API Endpoints:**
- Import `api-endpoints-postman.json` into Postman

**StockPickr**
In the "StockPickr" folder you can find the source code of StockPickr which is a production-ready finance application. Please, first, read the "Case Study: Building A Finance App."