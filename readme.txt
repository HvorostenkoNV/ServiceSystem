INSTALLATION

1.  PHP 7.3+
2.  PHP CURL
3.  Redis
4.  MySql
5.  Create DB table
        service_system
            sum         int(255)    not null
            count_fib   int(11)     not null
            count_prime int(255)    not null
6.  Create one record
        sum count_fib   count_prime
        0   0           0
7.  Download "serviceSystem" directory to your web server
8.  Make sure "serviceSystem/public" directory is reachable by HTTP
    Make sure "serviceSystem/core" directory is executable by your server
    Make sure "serviceSystem/params" directory is readable by your server
9.  Edit "serviceSystem/params/systemParameters.txt" file to specify system settings
    fibonacci.generationSize        -   fibonacci numeric sequence size
    fibonacci.generationDelay       -   fibonacci generation delay
    fibonacci.generatorUri          -   fibonacci generator public access file.
                                        URI path to "serviceSystem/public/generatorFibonacci.php" file
                                        For example:
                                            10.10.0.10/serviceSystem/public/generatorFibonacci.php
                                            http://my-server.ua/generatorFibonacci.php (if any server redirect rules enabled)
    fibonacci.clientUri             -   fibonacci client public access file.
                                        URI path to "serviceSystem/public/clientFibonacci.php" file
                                        Same rules as for "fibonacci.generatorUri" parameter
    fibonacci.dataBusChanel         -   fibonacci HTTP data-bus chanel URI client public access file.
                                        URI path to "serviceSystem/public/dataBus.php" file
                                        Use URI request parameter (URI GET parameter) "chanel" to indicate chanel
                                        For example:
                                            10.10.0.10/serviceSystem/public/dataBus.php?chanel=fibonacci
                                            10.10.0.10/serviceSystem/public/dataBus.php?chanel=AnyOtherChanelName
                                            http://my-server.ua/dataBus.php?chanel=fibonacci (if any server redirect rules enabled)

    primeNumber parameters group    -   same rules as for fibonacci parameters described above

    dataBase.host                   -   Database host
    dataBase.name                   -   Database name
    dataBase.login                  -   Database authentication login
    dataBase.password               -   Database authentication password

USAGE

1.  Run "serviceSystem/public/demonstration.php" file with your browser
    For example:
        10.10.0.10/serviceSystem/public/demonstration.php
        http://my-server.ua/demonstration.php (if any server redirect rules enabled)

SYSTEM STRUCTURE

1.  "serviceSystem/core"
        system core, classes
        contains classes autoloader "serviceSystem/core/include.php"
2.  "serviceSystem/params"
        system parameters directory
        contains one file only for now
3.  "serviceSystem/public"
        system public files
        "serviceSystem/public/generatorFibonacci.php"   -   run fibonacci numeric sequence generator
        "serviceSystem/public/generatorPrimeNumber.php" -   run prime numbers sequence generator
        "serviceSystem/public/clientFibonacci.php"      -   run fibonacci client listener
        "serviceSystem/public/clientPrimeNumber.php"    -   run prime numbers client listener
        "serviceSystem/public/dataBus.php"              -   data-bus external entry point
        "serviceSystem/public/demonstration.php"        -   demonstration script
                                                            clean up all already generated/saved data
                                                            run all generators and listeners at once
                                                            provides link to database condition interface
        "serviceSystem/public/databaseCurrentState.php" -   database current condition interface