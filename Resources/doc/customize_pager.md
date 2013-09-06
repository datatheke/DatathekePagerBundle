``` php
<?php
    /**
     * @Template()
     */
    public function pagerAction(Request $request)
    {
        $pager = $this->get('datatheke.pager')->createWebPager('MyBundle:MyEntity', array(
            'item_count_per_page' => 2,
            )
        );

        $pager->handleRequest($request);

        return array('pager' => $pager);
    }
```