<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

interface PagerViewInterface extends StaticPagerInterface
{
    public function getFilterParam();

    public function getOrderByParam();

    public function getCurrentPageNumberParam();

    public function getItemCountPerPageParam();

    public function getRoute();

    public function getParameters();
}
