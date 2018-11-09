<?php

namespace Engine\Core;

use Engine\Controllers\AController;
use Engine\Interfaces\IRoute;

/**
 * Class Routing
 * news - вывести список новостей
 * news?sort=ask&page=1 - вывести список новостей отсортированные по возростанию, 1-ю страницу пагинации
 * news/add - добавить новость
 * news/update/2 - обновить новость с id 2
 * news/delete/3 - удалить новость с id 3
 * news/2 - вывести подробное описание новости с id 2
 * @package Engine\Core
 */
class Routing implements IRoute
{
    private $defaultController;
    private $defaultAction;
    private $routes = [];
    private $controllers = [];
    private $actions = [];
    private $errorPage;

    /**
     * Routing constructor.
     * @param array $options
     * @throws \Exception
     */
    public function __construct( array $options )
    {
        if ( !isset( $options[ 'defaultController' ] ) || empty( $options[ 'defaultController' ] ) ) {
            throw new \Exception( 'Не указан контроллер по умолчанию' );
        }

        if ( !isset( $options[ 'defaultAction' ] ) || empty( $options[ 'defaultAction' ] ) ) {
            throw new \Exception( 'Не указан экшен по умолчанию' );
        }

        if ( !isset( $options[ 'errorController' ] ) || empty( $options[ 'errorController' ] ) ) {
            throw new \Exception( 'Не указан для обработки ошибок' );
        }

        $this->defaultController =  $options[ 'defaultController' ];
        $this->defaultAction = 'action' . ucfirst( $options[ 'defaultAction' ] );
        $this->errorPage = $options[ 'errorController' ];
    }

    /**
     * Добавить правило обработки роута.
     * @param string $route - Регулярное выражение для проверки соответствия роуту.
     * @param string $controller - Контроллер.
     * @param string $action - Экшен.
     */
    public function add( string $route, string $controller, string $action ): void
    {
        $this->routes[] = $route;
        $this->controllers[] = $controller;
        $this->actions[] = $action;
    }

    /**
     * Обработка запроса.
     * @param array $request
     * @throws \Exception
     */
    public function submit( array $request ): void
    {
        if ( $this->checkPath( $request ) ) {
            $path = trim( $request[ 'path' ], '/' );
            $params = array_slice( $request, 1 );

            $controller = $this->getController( $path );
            $controllerName = ( $controller[ 'name' ] ?? null );
            $controllerAction = ( $controller[ 'action' ] ?? null );
            $actionArg = ( explode( '/', $path )[ 1 ] ?? null );

            if ( class_exists( $controllerName ) ) {
                $instance = new $controllerName( $params );
                $this->executeAction( $instance, $controllerAction, $actionArg );
            } else {
                $instance = new $this->errorPage();
                $this->executeAction( $instance, 'action404' );
            }

        } else {
            if ( !class_exists( $this->defaultController ) ) {
                throw new \Exception( "Контроллер заданный по умолчанию {$this->defaultController} не существует" );
            }

            $defaultPage = new $this->defaultController();
            $this->executeAction( $defaultPage, $this->defaultAction );
        }
    }

    /**
     * Выполнить экшен.
     * @param AController $object
     * @param string $action
     * @param null $arg
     */
    private function executeAction( AController $object, string $action, $arg = null ): void
    {
        if ( $object instanceof AController ) {
            if ( method_exists( $object, $action ) ) {
                if ( $arg !== null  ) {
                    $object->$action( $arg );
                } else {
                    $object->$action();
                }
            }
        }
    }

    /**
     * Проверка корректности полученного запроса.
     * @param array $path
     * @return bool
     */
    private function checkPath( array $path ): bool
    {
        return ( is_array( $path ) && !empty( $path ) && key_exists( 'path', $path ) );
    }

    /**
     * Получить информацию о контроллере и экшене, которые соответствуют указанному маршруту.
     * @param string $path
     * @return array
     */
    private function getController( string $path ): array
    {
        $controller = [];

        foreach ( $this->routes as $key => $value ) {
            if ( preg_match( '/^' . $value . '$/i', $path ) === 1 ) {
                $controller[ 'name' ] = $this->controllers[ $key ];
                $controller[ 'action' ] = 'action' . ucfirst( $this->actions[ $key ] );
                break;
            }
        }

        return $controller;
    }
}
