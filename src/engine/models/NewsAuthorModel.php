<?php

namespace Engine\Models;

use Engine\Core\Db;

class NewsAuthorModel extends AModel
{
    private const TABLE_NEWS = 'news';
    private const TABLE_AUTHORS = 'authors';

    /**
     * Получить новости автора.
     * @param int $authorId
     * @param int $currentPage
     * @param int $countOnPage
     * @param string $sortDirection
     * @return array
     */
    public static function getNewsAuthorById( int $authorId, int $currentPage, int $countOnPage, string $sortDirection = 'DESC' ): array
    {
        $direction = self::getSortDirection( $sortDirection );
        $start = self::getStartPage( $currentPage, $countOnPage );

        $query = "SELECT n.id, FROM_UNIXTIME(n.publish_date) AS publish_date, n.picture, n.text, n.title, n.author_id, 
                         a.name AS author_name
                  FROM " . self::TABLE_NEWS . " n JOIN " . self::TABLE_AUTHORS . " a ON n.author_id = a.id
                  WHERE n.author_id = :author_id
                  ORDER BY publish_date {$direction}
                  LIMIT {$start},{$countOnPage}";

        $param = [ 'author_id' => $authorId ];
        $result = Db::getInstance()->select( $query, $param );

        return $result;
    }

    /**
     * Получить количество новостей.
     * @param int $authorId
     * @return int
     */
    public static function getCount( int $authorId ): int
    {
        return Db::getInstance()->getCountRow( self::TABLE_NEWS, [ 'author_id' => $authorId ] );
    }
}