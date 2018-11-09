<?php

namespace Engine\Models;

use Engine\Core\Db;

class NewsModel extends AModel
{
    private const TABLE = 'news';

    /**
     * Получить список новостей.
     * @param int $currentPage
     * @param int $countOnPage
     * @param string $sortDirection
     * @return array
     */
    public static function getList( int $currentPage, int $countOnPage, string $sortDirection = 'DESC' ): array
    {
        $direction = self::getSortDirection( $sortDirection );
        $start = self::getStartPage( $currentPage, $countOnPage );

        $query = "SELECT n.id, FROM_UNIXTIME(n.publish_date) AS publish_date, n.picture, n.text, n.title, n.author_id, 
                         a.name AS author_name
                  FROM " . self::TABLE . " n JOIN authors a ON n.author_id = a.id
                  ORDER BY publish_date {$direction}
                  LIMIT {$start},{$countOnPage}";

        $result = Db::getInstance()->select( $query );

        return $result;
    }

    /**
     * Получить новость по id.
     * @param integer $id
     * @return array
     */
    public static function getById( int $id ): array
    {
        $query = 'SELECT n.id, FROM_UNIXTIME(n.publish_date) AS publish_date, n.text, n.title, n.author_id, 
                         a.name AS author_name
                  FROM ' . self::TABLE . ' n JOIN authors a ON n.author_id = a.id
                  WHERE n.id = :id';
        $param = [ 'id' => $id ];
        $result = Db::getInstance()->select( $query, $param, true );

        return $result;
    }

    /**
     * Получить количество новостей.
     * @return int
     */
    public static function getCount(): int
    {
        return Db::getInstance()->getCountRow( self::TABLE );
    }
}