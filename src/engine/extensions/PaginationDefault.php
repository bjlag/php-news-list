<?php

namespace Engine\Extensions;

/**
 * Class PaginationDefault
 * @package Engine\Extensions
 */
class PaginationDefault extends AExtension
{
    /**
     * @param array $options - Параметры пагинации.
     * int $options[0] - Общее число записей.
     * int $options[1] - Число записей выводящихся на странице.
     * int $options[2] - Текущая страница.
     * @return mixed
     */
    public function render( array $options = null )
    {
        [ $count, $countOnPage, $currentPage, $route ] = $options;

        $totalPage = ceil( $count / $countOnPage );

        if ( $currentPage > $totalPage ) {
            $previous = $totalPage - 1;
            $next = $totalPage;
        } else {
            $previous = ( $currentPage > 1 ? $currentPage - 1 : 1 );
            $next = ( $currentPage < $totalPage ? $currentPage + 1 : $totalPage );
        }

        $nav = [
            'currentPage' => $currentPage,
            'totalPage' => $totalPage,
            'items' => [
                [
                    'title' => 'Назад',
                    'url' => '/' . $route . '?' . $this->getUrlParams( $previous ),
                    'disable' => ( $currentPage == 1 ),
                ],
                [
                    'title' => 'Вперед',
                    'url' => '/' . $route . '?' . $this->getUrlParams( $next ),
                    'disable' => ( $currentPage == $totalPage )
                ]
            ]
        ];

        return $this->exportTemplate( $nav );
    }

    /**
     * @param string $page
     * @return string
     */
    private function getUrlParams( string $page ): string
    {
        return http_build_query(
            array_merge(
                array_slice( $_GET, 1 ) ?? [],
                [ 'page' => $page ]
            )
        );
    }
}