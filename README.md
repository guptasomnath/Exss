# Exss
Exss is a library for creating an asynchronous HTTP server using PHP. It is built on top of the ReactPHP framework. With this library, you can create your asynchronous web app using PHP, and it is similar to Express.js.


## Installation

Use the package manager [Composer](https://getcomposer.org/) to install Exss.

```bash
composer require exssah/exss
```

## Usage

Basic http server
```php
require __DIR__ . '/vendor/autoload.php';

use Exssah\Exss\Exss;
use Exssah\Exss\Req;
use Exssah\Exss\Res;

# create a object of Exss class
$app = new Exss();

# use route methods to accept asynchronous HTTP requests
$app::get('/hello', function(Req $req, Res $res){
  return $res::send('Hello world');
});

# start the server at port 8080
$app::listen(8080, function(){
    echo 'http://localhost:8080';
});
```
Accept The Request Parameters \
ex:- http://localhost:8080/user?id=5&name=Somnath Gupta

```php
require __DIR__ . '/vendor/autoload.php';

use Exssah\Exss\Exss;
use Exssah\Exss\Req;
use Exssah\Exss\Res;

# create a object of Exss class
$app = new Exss();

# use route methods to accept asynchronous HTTP requests
#ex:- http://localhost:8080/user?id=5&name=Somnath Gupta

$app::get('/user', function(Req $req, Res $res){

  $userID = $req::params('id'); #'null' if not exist
  $userName = $req::params('name'); #'null' if not exist

 #use the sendJson method to send a JSON response
  return $res::sendJson([
    'id' => $userID,
    'name' => $userName,
  ]);

});

# start the server at port 8080
$app::listen(8080, function(){
    echo 'http://localhost:8080';
});
```

Accept The Request Body \
\
URl:- http://localhost:8080/user \
\
Body\
{\
 "username" : "Somnath Gupta", \
 "gmail" : "testgmail@gmail.com",\
 "password" : "U7%)#",\
}

```php
require __DIR__ . '/vendor/autoload.php';

use Exssah\Exss\Exss;
use Exssah\Exss\Req;
use Exssah\Exss\Res;

# create a object of Exss class
$app = new Exss();

# use route methods to accept asynchronous HTTP requests

$app::post('/user', function(Req $req, Res $res){

  $username = $req::body('username'); #'null' if not exist
  $gmail = $req::body('gmail'); #'null' if not exist
  $password = $req::body('password'); #'null' if not exist

 #use the sendJson method to send a JSON response
  return $res::sendJson([
    'username' => $username,
    'gmail' => $gmail,
    'password' => $password,
  ]);

});

# start the server at port 8080
$app::listen(8080, function(){
    echo 'http://localhost:8080';
});
```

## Your can use other fetchers of [ReactPHP framework](https://github.com/reactphp/reactphp)
[1) Promise](https://github.com/reactphp/promise) , [2) Async Utilities](https://github.com/reactphp/async), [3) Browser Api's](https://github.com/reactphp/http#browser)


## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.
