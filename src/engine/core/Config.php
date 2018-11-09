<?php

namespace Engine\Core;

class Config
{
    /** @var array $cache */
    private static $cache = [];

    /**
     * Получить параметр конфигурации.
     * @param string $param
     * @return string|null
     * @throws \Exception
     */
    public static function get( string $param ): ?string
    {
        if ( empty( self::$cache ) ) {
            self::$cache = self::loadConfigFile();
        }

        if ( key_exists( $param, self::$cache ) ) {
            return self::$cache[ $param ];
        }

        return null;
    }

    /**
     * Автоматическое подключение конфигурационных файлов.
     * @return array
     * @throws \Exception
     */
    private static function loadConfigFile(): array
    {
        $path = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'config';
        $config = [];

        if ( !is_dir( $path ) ) {
            throw new \Exception( "Директория {$path} с конфигурационными файлами не найдена" );
        }

        $files = array_diff( scandir( $path ), [ '.', '..' ] );
        if ( !$files ) {
            throw new \Exception( "Директория {$path} не содержит конфигурационные файлы" );
        }

        foreach ( $files as $file ) {
            if ( !preg_match( '/.conf.php$/', $file ) ) {
                continue;
            }

            require $path . DIRECTORY_SEPARATOR . $file;
        }

        return $config;
    }
}
