<?php

namespace Engine\Controllers;

use Engine\Core\Config;
use Engine\Interfaces\IExtension;
use Engine\Models\AuthorModel;
use Engine\Models\NewsAuthorModel;

class AuthorController extends AController
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
     * Вывод страницы.
     * @param int $id
     * @throws \Exception
     */
    public function actionShow( int $id ): void
    {
        $total = NewsAuthorModel::getCount( $id );
        $countOnPage = Config::get( 'NEWS_ON_PAGE' );
        $currentPage = $this->param[ 'page' ] ?? 1;
        $sortDirection = $this->param[ 'sort' ] ?? 'desc';

        $author = AuthorModel::getById( $id );

        if($author) {
            $news = NewsAuthorModel::getNewsAuthorById( $id, $currentPage, $countOnPage, $sortDirection );

            $data = [
                'title' => 'Новости автора ' . $author[ 'name' ],
                'header' => 'Новости автора ' . $author[ 'name' ],
                'content' => $news,
                'options' => [
                    'previewLength' => Config::get( 'NEWS_LENGTH_PREVIEW_TEXT' ),
                    'dateFormat' => Config::get( 'DATE_FORMAT' )
                ],
                'extensions' => [
                    'pagination' => $this->pagination->render( [ $total, $countOnPage, $currentPage, "author/{$id}" ] ),
                    'sortbar' => $this->sortbar->render( [ $sortDirection, "author/{$id}" ] )
                ]
            ];

            $this->includeTemplate( 'author/show', $data );
        } else {
            $this->showError404();
        }
    }
}