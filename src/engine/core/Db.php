<?php

namespace Engine\Core;

use Engine\Traits\TSingleton;
use PDO;

class Db
{
    use TSingleton;

    /** @var PDO */
    private $link = null;

    private function __construct()
    {
        setlocale( LC_ALL, 'ru_RU.UTF8' );
        $this->link = $this->connect();
    }

    private function __clone() {}

    private function connect()
    {
        $dsn =  Config::get( 'DB_DRIVER' )
            . ':host=' . Config::get( 'DB_HOST' )
            . ';dbname=' . Config::get( 'DB_DATABASE' )
            . ';charset=' . Config::get( 'DB_CHARSET' );

        $db = new PDO( $dsn, Config::get( 'DB_USER' ), Config::get( 'DB_PASSWORD' ) );

        $db->exec( 'SET NAMES ' . Config::get( 'DB_CHARSET' ) );
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

        return $db;
    }

    /**
     * Выборка данных.
     * SELECT * FROM user WHERE id_user=:id_user
     * @param string $query - текст запроса с именованными параметрами, например, :id_user
     * @param array $params - ассоциативный массив содержащий значения именовынных параметров
     * @param bool $one - если TRUE, то метод вернет только одну зупись (первую)
     * @return array
     */
    public function select( $query, array $params = [], $one = false ): array
    {
        $sth = $this->link->prepare( $query );
        $sth->execute( $params );

        if ( $one ) {
            $result = $sth->fetch();
        } else {
            $result = $sth->fetchAll();
        }

        return ( $result ? $result : [] );
    }

    /**
     * Получить количество записей в таблице.
     * @param string $table - Имя таблицы, в которой определяется количество записей.
     * @param array|null $where
     * @return int
     */
    public function getCountRow( string $table, array $where = [] ): int
    {
        $query = "SELECT COUNT(*) AS c FROM {$table}";

        if ( !empty( $where ) ) {
            $prepare = [];
            foreach ( $where as $key => $value ) {
                $prepare[] = "{$key}=:{$key}";
            }

            $query .= " WHERE " . implode( ' AND ', $prepare );
        }

        $sth = $this->link->prepare( $query );
        $sth->execute( $where );

        $result = $sth->fetch();

        return $result[ 'c' ] ?? 0;
    }
}
