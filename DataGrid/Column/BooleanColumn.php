<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

class BooleanColumn extends AbstractColumn
{
    public function getType()
    {
        return 'boolean';
    }
}
