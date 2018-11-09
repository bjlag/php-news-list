<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 19.10.2018
 * Time: 16:25
 */

namespace Engine\Models;


abstract class AModel
{
    protected static function getSortDirection( string $direction ): string
    {
        $direction = strtoupper( $direction );
        return ( $direction != 'DESC' && $direction != 'ASC' ? 'DESC' : $direction );
    }

    protected static function getStartPage( int $currentPage, int $countOnPage ): int
    {
        return ( $currentPage * $countOnPage ) - $countOnPage;
    }
}