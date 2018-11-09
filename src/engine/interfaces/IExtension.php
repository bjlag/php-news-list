<?php

namespace Engine\Interfaces;

interface IExtension
{
    /**
     * @param array $options
     * @return mixed
     */
    public function render( array $options = null );
}