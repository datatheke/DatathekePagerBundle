See http://flexigrid.info/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function flexigridAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'flexigrid');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/flexigrid/css/flexigrid.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/flexigrid/js/flexigrid.js') }}" type="text/javascript"></script>

    <script>

        $("#flexigrid").flexigrid({
            url: "{{ path('handlers_flexigrid') }}",
            dataType: 'json',
            colModel : [
                {display: 'Name', name : 'name', width : 40, sortable : true, align: 'center'},
                {display: 'Country', name : 'country', width : 180, sortable : true, align: 'left'}
                ],
            searchitems : [
                {display: 'Name', name : 'name'},
                {display: 'Country', name : 'country', isdefault: true}
                ],
            sortname: "name",
            sortorder: "asc",
            usepager: true,
            title: 'Demo',
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 700,
            height: 200
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>Flexigrid</h1>

        <table id="flexigrid" style="display:none"></table>
    </section>
{% endblock %}
```