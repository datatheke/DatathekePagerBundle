<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

class Configuration
{
    protected $theme;

    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    public function getTheme()
    {
        return $this->theme;
    }
}
