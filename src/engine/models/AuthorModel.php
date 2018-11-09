<?php

namespace Engine\Models;

use Engine\Core\Db;

class AuthorModel
{
    /**
     * Получить автора по его ID.
     * @param int $id
     * @return array
     */
    public static function getById( int $id ): array
    {
        $query = 'SELECT * FROM authors WHERE id = :id';
        $param = [ 'id' => $id ];
        $result = Db::getInstance()->select( $query, $param, true );

        return $result;
    }
}