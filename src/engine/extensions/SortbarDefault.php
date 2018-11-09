<?php

namespace Engine\Extensions;


class SortbarDefault extends AExtension
{
    /**
     * @param array $options
     * $options[0] - Направление сортировки
     * $options[1] - Роут
     * @return mixed
     */
    public function render( array $options = null)
    {
        return $this->exportTemplate( [ 'direction' => $options[ 0 ], 'route' => $options[ 1 ] ] );
    }
}