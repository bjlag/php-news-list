<?php
include_once dirname( __DIR__ ) . '/vendor/autoload.php';

use Engine\Controllers\{AuthorController, ErrorController, NewsController};
use Engine\Core\{App, Config, Routing};

try {
    $route = new Routing( [
        'defaultController' => NewsController::class,
        'defaultAction' => 'list',
        'errorController' => ErrorController::class
    ] );

    $route->add( 'news', NewsController::class, 'list' );
    $route->add( 'news\/\d+', NewsController::class, 'show' );
    $route->add( 'author\/\d+', AuthorController::class, 'show' );

    $app = new App( $route, Config::get( 'TIMEZONE' ) );
    $app->start();

} catch ( Exception $e ) {
    echo $e->getMessage();
}
