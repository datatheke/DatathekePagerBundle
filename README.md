DatathekePagerBundle
=================

ABOUT
-----

Pager & DataGrid bundle for Symfony2

Main features are :
 - HTTP or Console mode
 - Connectors to javascript's libraries (jqGrid, Jquery Autocomplete). More to come (Bootstrap TypeAhead, DataTables, Dynatable, SlickGrid, ...)


LICENSE
-------

MIT (see LICENSE file)

USAGE
-----

More exemples in the [documentation](https://github.com/datatheke/DatathekePagerBundle/tree/master/Resources/doc)

## Datagrid

PHP

``` php
<?php
    /**
     * @Template()
     */
    public function datagridAction()
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity');
        $view = $datagrid->handleRequest($this->getRequest());

        return array('datagrid' => $view);
    }
```

TWIG

``` html+django
    {{ datagrid(datagrid) }}

    {# OR BETTER #}

    {% extends '::base.html.twig' %}

    {% block stylesheets %}
        {{ parent() }}
        {{ datagrid_stylesheets(datagrid) }}
    {% endblock %}

    {% block javascripts %}
        {{ parent() }}
        {{ datagrid_javascripts(datagrid) }}
    {% endblock %}

    {% block body %}
        <div class="container">
            <h1>Test DataGrid</h1>
            {{ datagrid_content(datagrid) }}
        </div>
    {% endblock %}
```

## Pager

PHP

``` php
<?php
    /**
     * @Template()
     */
    public function pagerAction()
    {
        $pager = $this->get('datatheke.pager')->createHttpPager('MyBundle:MyEntity');
        $view = $pager->handleRequest($this->getRequest());

        return array('pager' => $view);
    }
```

TWIG

``` html+django
    {% extends '::base.html.twig' %}

    {% import 'DatathekePagerBundle:Pager:bootstrap3.html.twig' as helper %}

    {% block stylesheets %}
        {{ parent() }}
        {{ helper.stylesheets() }}
    {% endblock %}

    {% block javascripts %}
        {{ parent() }}
        {{ helper.javascripts() }}
    {% endblock %}

    {% block body %}
        <div class="container">
            <h1>Test pager</h1>
            <form action="{{ pager_form_path(pager) }}" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ helper.toolbar(pager) }}
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{ helper.orderBy(pager, 'firstname', 'Firstname') }}</th>
                                <th>{{ helper.orderBy(pager, 'lastname', 'Lastname') }}</th>
                            </tr>
                            <tr>
                                <th>{{ helper.filter(pager, 'firstname') }}</th>
                                <th>{{ helper.filter(pager, 'lastname') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for row in pager.items %}
                                <tr>
                                    <td>{{ row.firstname }}</td>
                                    <td>{{ row.lastname }}</td>
                                </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>

                {{ helper.paginate(pager) }}
            </form>
        </div>
    {% endblock %}
```

INSTALL
-------

### Install with composer

    composer.phar require "datatheke/pager-bundle" "~0.3"

### Update your app/AppKernel.php

``` php
<?php
    //...
    $bundles = array(
        //...
        new Datatheke\Bundle\PagerBundle\DatathekePagerBundle(),
    );
```
