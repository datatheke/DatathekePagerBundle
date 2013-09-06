``` php
<?php
    /**
     * @Template()
     */
    public function pagerAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createWebDataGrid('MyBundle:MyEntity');

        // Add a column 'Actions' with 2 buttons
        $actions = new StaticColumn('Actions');
        $actions->addAction(new Action('View', 'item_view', array(
            'icon' => '.glyphicon-eye-open'
            )
        ));
        $actions->addAction(new Action('Delete', 'item_delete', array(
            'item_mapping' => array('id' => 'lastname') // use column 'lastname' for the route parameter 'id' (default mapping is 'id' => 'id')
            )
        ));
        $datagrid->addColumn($actions, 'action');

        // Sort columns
        $datagrid->sortColumns(array('action', 'firstname', 'lastname'));

        // Hide column 'firstname'
        $datagrid->getColumn('firstname')->hide();

        $datagrid->handleRequest($request);

        return array('datagrid' => $datagrid);
    }
```