<?php

namespace Engine\Controllers;

/**
 * Class ErrorController
 * @package Engine\Controllers
 */
class ErrorController extends AController
{
    /**
     * Вывод 404 ошибки.
     */
    public function action404(): void
    {
        header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );

        $this->includeTemplate( 'errors/404' );
    }
}
