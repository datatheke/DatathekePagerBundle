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

    $pager = $this->get('datatheke.pager')->createWebPager('MyBundle:MyEntity', array(
        // Static parameter for routing
        'parameters' => array('id' => '42')
        )
    );

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

    $datagrid = $this->get('datatheke.datagrid')->createWebDataGrid($pager);

    // Change column label
    $datagrid->getColumn('city')->setLabel('Office city');

    $datagrid->handleRequest($this->getRequest());

    return array('datagrid' => $datagrid);
}
```