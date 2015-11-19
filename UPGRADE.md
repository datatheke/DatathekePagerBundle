-> v0.4.0
---------
Change Pager/PagerInterface.php
- Add 'name' argument to 'setFilter' method

v0.3.12 -> v0.3.13
------------------
- Datatheke\Bundle\PagerBundle\Pager::getMetadatas() is deprecated. Use Datatheke\Bundle\PagerBundle\Pager::getMetadata() instead

v0.3.11 -> v0.3.12
------------------
### Pager & Datagrid
- (CSS) Filter operator box now appears on input:focus, not :hover anymore

v0.3.4 -> v0.3.5
----------------
### Datagrid
- Change Datatheke\Bundle\PagerBundle\DataGrid\DataGridViewInterface
    -> new method :  public function getColumn($alias)

- Change Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\ActionInterface
    -> public function getParameters(Datagrid $datagrid, $item) becomes public function getParameters(DatagridView $datagrid, $item)
    -> public function evaluateDisplay(Datagrid $datagrid, $item) becomes public function evaluateDisplay(DatagridView $datagrid, $item)

- Change Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\Action according to ActionInterface changes

v0.3.3 -> v0.3.4
----------------
### Datagrid
- In Factory, changed createHttpDataGrid() signature from
public function createHttpDataGrid($pager, array $options = array(), array $columns = null, $handler = 'view')
to
public function createHttpDataGrid($pager, array $options = array(), $handler = 'view', array $columns = null)

- In Factory, changed createConsoleDataGrid() signature from
public function createConsoleDataGrid($pager, array $options = array(), array $columns = null, $handler = 'default')
to
public function createConsoleDataGrid($pager, array $options = array(), $handler = 'default', array $columns = null)

v0.3.1 -> v0.3.2
----------------
### Pager & Datagrid
- jgGridHandler class has been renamed from jqGridHandler to JqGridHandler

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
