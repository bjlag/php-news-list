<?php

namespace Engine\Core;

use Engine\Interfaces\IRoute;


class App
{
    private $routing;

    public function __construct( IRoute $routing, string $timezone )
    {
        date_default_timezone_set( $timezone );

        $this->routing = $routing;
    }

    /**
     * Запуск приложения.
     */
    public function start(): void
    {
        if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' && isset( $_GET ) ) {
            $this->routing->submit( $_GET );
        }
    }
}
