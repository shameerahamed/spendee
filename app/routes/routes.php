<?php
  use HTTP\Middleware\AuthMiddleware;
  use \HTTP\Middleware\Guest;

  // Vissible by guest users
  $app->group('',function(){
      require 'home/home.php';
  })->add($container->csrf)->add(new Guest($container));

  //Vissible by logged in users
  $app->group('',function() use($app){
    require 'main/main.php';
  })->add($container->csrf)->add(new AuthMiddleware($container));

  // API
  $app->group('/api',function(){
      require 'api/routes.php';
  })->add($container->csrf)->add(new AuthMiddleware($container));

  $app->group('/api',function(){
      require 'mobile/routes.php';
  });
  //Vissible by all users
  require 'auth/auth.php';
  require 'errors/errors.php';
 ?>
