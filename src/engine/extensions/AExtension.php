<?php

namespace Engine\Extensions;

use Engine\Core\Template;
use Engine\Interfaces\IExtension;

abstract class AExtension implements IExtension
{
    protected $template;

    /**
     * PaginationDefault constructor.
     * @param string $template
     */
    public function __construct( string $template )
    {
        $this->template = $template;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function exportTemplate( array $data )
    {
        return Template::getInstance()->renderBlock( $this->template, [ 'data' => $data ] );
    }
}