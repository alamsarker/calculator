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
   * Run `mv .env.example .env`.
   * Run `make` or `make start`. If it's not working, then try to use `sudo` as `sudo make start`.
   * Now go to another tab, and go to the project directory.
   * Run `make composer` to install vendor packages.
   * Run `make migrate` to create database tables.
   * Now the system is ready to run.
   * Run `make phpunit` for unit test. For the first time, it will download packages.

All done? Lets browse:

* Calculator: http://localhost
* PHPMyAdmin: http://localhost:8082

## Feature Notes

1) Sale can't be done if stock is not available.
2) After saving purchase and sales, success message is not given.
3) Edit and delete action are not implemented for purchase and sale.
4) Assume that a single product is purchased and sold for generating profit.


## Technical Notes

1) Puchase sequence has maintained by autoincremented id asc. Purchase date may good for sequence but did not added for simplicity.
2) FIFO based profit has been calculated by MySQL query using variable. Though I'm aware of the following note, I still belive query is better than writing code.
> The order of evaluation for expressions involving user variables is undefined. For example, there is no guarantee that SELECT @a, @a:=@a+1 evaluates @a first and then performs the assignment.
>
> Reference: Please see [here](https://dev.mysql.com/doc/refman/8.0/en/user-variables.html).
3) The code has been implemented based on the problem. That's why productId is missing in purchase and sales table.
4) Basically `purchase` table will be `purchase_items` and `sales` table will be `sale_items`. For the simplicty,ralated necessary tables and fields are not included.
5) Symfony commnad has been used to generate code. Generated code with no business logic has excluded from unit test.
6) Purchase, Sales and Profit links are not activated once selected.
