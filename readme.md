# Margin Calculator

Margin calculator which would calculate the accumulated total profit from the given sequences of purchases and sales.


## Installation

Step 1: Prerequisites

    * Install docker
        * Ubuntu: https://docs.docker.com/install/linux/docker-ce/ubuntu/
        * Windows: https://docs.docker.com/docker-for-windows/install/
    * Install docker-compose
        * Ubuntu: https://docs.docker.com/compose/install/
        * Windows: Not necessary. Windows docker installer has docker-composer included.

    Run `docker -v` and `docker-compose -v` to make sure the installation.

Step 2: Install NginX, PHP, Mysql, PhpMyAdmin and vendor packages

   * Go to project directory.
   * Run `cp .env.example .env`.
   * Run `make` or `make start`. If it's not working, then try to use `sudo` as `sudo make start`. Also, there can be conflict on ports. Its needed to change the port form `docker-compose.yml` file.
   * Now, go to project directory again opening new tab.
   * Run `make composer` to install vendor packages.

All done? Lets browse:

* Calculator: http://localhost
* PHPMyAdmin: http://localhost:8082

## Unit Tests

`make phpunit` command will run both feature and unit tests. `calculator_test` database has been created for feature test and `dama/doctrine-test-bundle` package has been installed for reseting database for every test run. So, it won't be conflict for every test command. For the first time, it will download all necessary packages.

## Feature Notes

1) Sale can't be done if stock is not available.
2) After saving purchase and sales, success message is not given.
3) Edit and delete action are not implemented for purchase and sale.
4) Assume that a single product is purchased and sold for generating profit.
5) Purchase, Sales and Profit links are not activated once selected.


## Technical Notes

1) Puchase sequence has maintained by auto incremented id order by asc. Purchase date may be good for sequence but did not add for simplicity.
2) FIFO based profit has been calculated by MySQL query using variable. Though I'm aware of the following note, I still belive query is better than writing code.
> The order of evaluation for expressions involving user variables is undefined. For example, there is no guarantee that SELECT @a, @a:=@a+1 evaluates @a first and then performs the assignment.
>
> Reference: Please see [here](https://dev.mysql.com/doc/refman/8.0/en/user-variables.html).
3) The code has been implemented based on the problem. That's why productId is missing in purchase and sales table.
4) Basically `purchase` table will be `purchase_items` and `sales` table will be `sale_items`. For the simplicty,ralated necessary tables and fields are not included.
5) Database will be empty for each `make` commad - its intentional.
6) Skip adding comments on class and methods for preparaing documentation by phpDocs.

## System Configuration

4GB RAM has been distributed on four containers with each of `.5` core cpu of total 2 cpu core.

Example of response of 1K ( 20 concurrent) requests by `Ab` banchmarking tool with 4GB RAM and 2 cpu Core system.
```
➜  ~ docker stats
---------------------------------------------------------------------------------------------------------------------------------------------------------
CONTAINER ID        NAME                   CPU %               MEM USAGE / LIMIT     MEM %               NET I/O             BLOCK I/O           PIDS
---------------------------------------------------------------------------------------------------------------------------------------------------------
f142254c06f3        calculator_myadmin_1   0.05%               40.02MiB / 500MiB     8.00%               21.3kB / 0B         537kB / 0B          9
33859ac70f99        calculator_db_1        0.12%               195.8MiB / 1.465GiB   13.05%              20.5kB / 1.56kB     4.1kB / 320MB       28
ba200b6b2d2e        calculator_fpm_1       0.01%               60.79MiB / 1.465GiB   4.05%               1.38MB / 54.2MB     0B / 0B             4
fd9e52e2e14e        calculator_nginx_1     0.00%               3.066MiB / 500MiB     0.61%               55.1MB / 55.6MB     0B / 0B             2
---------------------------------------------------------------------------------------------------------------------------------------------------------

➜  ~ ab -n 1000 -c 20 http://localhost:80/
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1807734 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.11.1
Server Hostname:        localhost
Server Port:            80

Document Path:          /
Document Length:        52263 bytes

Concurrency Level:      20
Time taken for tests:   1083.061 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      52598000 bytes
HTML transferred:       52263000 bytes
Requests per second:    0.92 [#/sec] (mean)
Time per request:       21661.229 [ms] (mean)
Time per request:       1083.061 [ms] (mean, across all concurrent requests)
Transfer rate:          47.43 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.2      0       2
Processing:  1450 21480 2647.1  21905   26096
Waiting:     1449 21476 2647.1  21904   26095
Total:       1451 21480 2647.1  21905   26096

Percentage of the requests served within a certain time (ms)
  50%  21905
  66%  22607
  75%  23092
  80%  23302
  90%  23892
  95%  24301
  98%  24794
  99%  25194
 100%  26096 (longest request)
```
