``` php
<?php

/**
 * @Template()
 */
public function pagerAction(Request $request)
{
    $pager = $this->get('datatheke.pager')->createHttpPager('MyBundle:MyEntity', array(
        'item_count_per_page' => 2,
        )
    );

    $view = $pager->handleRequest($request);

    return array('pager' => $view);
}
```