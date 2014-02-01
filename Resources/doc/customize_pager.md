``` php
<?php

/**
 * @Template()
 */
public function pagerAction(Request $request)
{
    $pager = $this->get('datatheke.pager')->createHttpPager('MyBundle:MyEntity', array(
        'item_count_per_page' => 50,
        'item_count_per_page_choices' => array(10, 20, 50, 100)
        )
    );

    $view = $pager->handleRequest($request);

    return array('pager' => $view);
}
```