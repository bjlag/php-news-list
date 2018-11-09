<?php

namespace Engine\Core;

use Engine\Traits\TSingleton;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class Template
{
    use TSingleton;

    /** @var Twig_Environment  */
    private $twig;

    /**
     * Template constructor.
     * @throws \Exception
     */
    private function __construct()
    {
        $loader = new Twig_Loader_Filesystem( Config::get( 'PATH_TEMPLATES' ) );
        $this->twig = new Twig_Environment( $loader, [
            'auto_reload' => true,
            'cache' => Config::get( 'PATH_CACHE_TEMPLATES' ),
            'debug' => Config::get( 'DEBUG' )
        ] );

        if ( Config::get( 'DEBUG' ) ) {
            $this->twig->addExtension( new Twig_Extension_Debug() );
        }
    }

    private function __clone() {}

    /**
     * Подготовка и вывод шаблона.
     * @param string $template
     * @param array $data
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render( string $template, array $data ): void
    {
        if ( is_null( $template ) ) {
            throw new \Exception( 'Не указан шаблон для вывода страницы' );
        }

        $content = $this->renderBlock( $template, [
            'header' => ( $data[ 'header' ] ?? '' ),
            'data' => ( $data[ 'content' ] ?? '' ),
            'options' => ( $data[ 'options' ] ?? [] ),
            'extensions' => ( $data[ 'extensions' ] ?? [] )
        ] );

        $layout = $this->twig->load( 'layout.tmpl' );
        $pageLayout = $layout->render( [
            'title' => ( $data[ 'title' ] ?? '' ),
            'content' =>  $content
        ] );

        echo $pageLayout;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderBlock( string $template, array $data )
    {
        $block = $this->twig->load( $template . '.tmpl' );
        return $block->render( $data );
    }
}
