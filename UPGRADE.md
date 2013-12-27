v0.2 -> v0.3
------------

### Pager
- Pager:handleRequest() returns now a PagerView object (with the default handler) that you must give to your template.
- Pager\Factory::createWebPager() is deprecated, use createHttpPager().
- Pager::__construct() has changed.

Before :
```php
<?php
    /*..*/
    $pager = $this->get('datatheke.pager')->createWebPager('MyBundle:MyEntity');
    $pager->handleRequest($this->getRequest());

    return array('pager' => $pager);
```
After :
```php
<?php
    /*..*/
    $pager = $this->get('datatheke.pager')->createHttpPager('MyBundle:MyEntity');
    $view = $pager->handleRequest($this->getRequest());

    return array('pager' => $view);
```

### DataGrid
- DataGrid:handleRequest() returns now a DataGridView object (with the default handler) that you must give to your template.
- DataGrid\Factory::createWebDataGrid() is deprecated, use createHttpDataGrid().

Before :
```php
<?php
    /*..*/
    $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity');
    $datagrid->handleRequest($this->getRequest());

    return array('datagrid' => $view);
```
After :
```php
<?php
    /*..*/
    $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity');
    $view = $datagrid->handleRequest($this->getRequest());

    return array('datagrid' => $view);
```
