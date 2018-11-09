<?php

namespace Engine\Controllers;

use Engine\Core\{Config, Template};
use Engine\Extensions\{PaginationDefault, SortbarDefault};
use Engine\Interfaces\IExtension;

/**
 * Class ControllerAbstract
 * @package Engine\Controllers
 */
abstract class AController
{
    /** @var array */
    protected $param = null;

    /**
     * ControllerAbstract constructor.
     * @param array $param
     */
    public function __construct( ?array $param = null )
    {
        if ( $param !== null ) {
            $this->param = $param;
        }
    }

    /**
     * Подключить переданный шаблон.
     * @param string $template - Имя шаблона.
     * @param array $data - Данные для вывода.
     */
    protected function includeTemplate( string $template, array $data = [] ): void
    {
        /** @var Template */
        Template::getInstance()->render( $template, $data );
    }

    /**
     * Вывод страницы с 404 ошибкой.
     */
    protected function showError404()
    {
        $pageError = new ErrorController();
        $pageError->action404();
    }

    /**
     * Получить инстенс класса, работающего с пагинацией.
     * @return PaginationDefault
     * @throws \Exception
     */
    protected function getPagination(): IExtension
    {
        return new PaginationDefault( Config::get( 'NEWS_PAGINATION_TEMPLATE' ) );
    }

    /**
     * Получить инстенс класса, работающего с панелью для сортировки.
     * @return SortbarDefault
     * @throws \Exception
     */
    protected function getSortbar(): IExtension
    {
        return new SortbarDefault( Config::get( 'NEWS_SORTBAR_TEMPLATE' ) );
    }
}
