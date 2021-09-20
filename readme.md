# Recruitment task - documentation app

The following documentation describes in more detail the requirements and capabilities of the application

## Requirements

* PHP 8
* Composer 2
* Mysql

## First steps

Remember to add the login, password and database name to the .env file.

```
    $ composer install
    $ php bin/console d:d:c
    $ php bin/console doctrine:migrations:migrate
    $ php bin/console cache:clear
```

## Downloading data from api

After entering the following command, the application will try to retrieve the data from the api and save them in the database.

### optAd360

```
    $ php bin/console app:fetch:optAd360
```

### NBP

```
    $ php bin/console app:fetch:nbp
```

## API

Application allows you to query your own enports to see the stored data.

### Setting

Returns all available settings stored in the database
```
    /api/setting/get
```

Returns one available setting stored in the database by id
```
    /api/setting/get/{id}
```

### Header

Returns all available headers stored in the database
```
    /api/header/get
```

Returns one available header stored in the database by id
```
    /api/header/get/{id}
```

### Currency

Returns all available currencies stored in the database
```
    /api/currency/get
```

Returns one available currency stored in the database by id
```
    /api/currency/get/{id}
```