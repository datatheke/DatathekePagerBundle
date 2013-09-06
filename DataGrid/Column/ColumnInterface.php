<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

interface ColumnInterface
{
    public function getLabel();

    public function getField();

    public function format($value);

    public function initialize();

    public function getType();

    public function isFilterable();

    public function isSortable();

    public function isVisible();
}
