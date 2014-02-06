``` php
<?php

/**
 * @Template()
 */
public function testAction(Request $request)
{
    /**
     * Create a custom pager
     */

    $pager = $this->get('datatheke.pager')->createPager('MyBundle:MyEntity');

    /**
     * Customize the QueryBuilder
     */

    // Retrieve QueryBuilder to join on the Adress Entity
    $qb = $pager->getAdapter()->getQueryBuilder();
    $qb->addSelect('a')->leftJoin('e.adress', 'a');

    // Add field 'city' to the pager
    $pager->getAdapter()->addField(new Field('adress.city', Field::TYPE_STRING, 'a.city'), 'city');

    /**
     * Create the DataGrid
     */

    $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid($pager);

    // Change column label
    $datagrid->getColumn('city')->setLabel('Office city');

    $view = $datagrid->handleRequest($request);

    return array('datagrid' => $view);
}
```