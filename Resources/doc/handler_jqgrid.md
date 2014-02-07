See http://www.trirand.com/blog/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function jggridAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'jqgrid');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/jquery-ui/themes/base/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('bundles/datathekedemo/vendor/jqgrid/css/ui.jqgrid.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/jquery-ui/js/jquery-ui.js') }}" type="text/javascript"></script>

    <script src="{{ asset('bundles/datathekedemo/vendor/jqgrid/js/i18n/grid.locale-fr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/jqgrid/js/jquery.jqGrid.min.js') }}" type="text/javascript"></script>

    <script>

        $('#jqGrid').jqGrid({
            url: "{{ path('handlers_jqgrid') }}",
            datatype: 'json',
            colNames: ['Name', '22'],
            colModel: [
                      {name: 'name', index: 'name'},
                      {name: 'country', index: 'country'},
                      ],
            jsonReader: { repeatitems : false },
            rowNum: 2,
            rowList: [10, 50, 100],
            pager: '#jqGridPager',
            viewrecords: true,
            caption: "jqGrid Handler"
          });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>jqGrid</h1>

        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
    </section>
{% endblock %}
```