DatathekePagerBundle
=================

ABOUT
-----

Work in progress

LICENSE
-------

MIT

EXEMPLES
--------

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
        $datagrid = $this->get('datatheke.datagrid')->createWebDataGrid('MyBundle:MyEntity');
        $datagrid->handleRequest($this->getRequest());

        return array('datagrid' => $datagrid);
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
        $pager = $this->get('datatheke.pager')->createWebPager('MyBundle:MyEntity');
        $pager->handleRequest($this->getRequest());

        return array('pager' => $pager);
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

INSTALLATION
------------

Will be available soon on packagist (composer)
