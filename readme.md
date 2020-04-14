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

`make phpunit` command will run both feature and unit tests. `calculator_test` database has been created for feature test and `ama/doctrine-test-bundle` package has been installed for reseting database for every test run. So, it won't be conflict for every test command. For the first time, it will download all necessary packages.

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
6)


## Sytem Configuration

Four container has been used with each of .5 core of total 2 CPU core and 4GB RAM has been distributed that can bee seen by `docker stats` command.
