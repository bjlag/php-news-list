<?php

namespace Engine\Controllers;

use Engine\Core\Config;
use Engine\Interfaces\IExtension;
use Engine\Models\NewsModel;

class NewsController extends AController
{
    /** @var IExtension $pagination */
    private $pagination;
    /** @var IExtension $sortbar */
    private $sortbar;

    /**
     * NewsController constructor.
     * @param array|null $param
     * @throws \Exception
     */
    public function __construct( ?array $param = null )
    {
        parent::__construct( $param );

        $this->pagination = $this->getPagination();
        $this->sortbar = $this->getSortbar();
    }

    /**
     * Экшен. Вывод списка новостей.
     * @throws \Exception
     */
    public function actionList(): void
    {
        $total = NewsModel::getCount();
        $countOnPage = Config::get( 'NEWS_ON_PAGE' );
        $currentPage = $this->param[ 'page' ] ?? 1;
        $sortDirection = $this->param[ 'sort' ] ?? 'desc';

        $data = [
            'title' => 'Список новостей',
            'header' => 'Список новостей',
            'content' => NewsModel::getList( $currentPage, $countOnPage, $sortDirection ),
            'options' => [
                'previewTextLength' => Config::get( 'NEWS_LENGTH_PREVIEW_TEXT' ),
                'dateFormat' => Config::get( 'DATE_FORMAT' )
            ],
            'extensions' => [
                'pagination' => $this->pagination->render( [ $total, $countOnPage, $currentPage, 'news' ] ),
                'sortbar' => $this->sortbar->render( [ $sortDirection, 'news' ] )
            ]
        ];

        $this->includeTemplate( 'news/list', $data );
    }

    /**
     * Экшен. Вывод конкретной новости.
     * @param int $id
     * @throws \Exception
     */
    public function actionShow( int $id ): void
    {
        $content = NewsModel::getById( $id );

        if ( $content ) {
            if ( $content ) {
                $data = [
                    'title' => $content[ 'title' ],
                    'header' => $content[ 'title' ],
                    'content' => $content,
                    'options' => [
                        'dateFormat' => Config::get( 'DATE_FORMAT' )
                    ]
                ];
            } else {
                $data = [
                    'title' => 'Новость не найдена',
                    'header' => 'Новость не найдена'
                ];
            }

            $this->includeTemplate( 'news/show', $data );
        } else {
            $this->showError404();
        }
    }
}
