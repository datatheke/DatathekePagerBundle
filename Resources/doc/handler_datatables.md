See http://www.datatables.net

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function dynatableAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'datatables');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/DataTables/media/css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery-1.11.0.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/DataTables/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>

    <script>

        $('#DataTables').dataTable({
            "bServerSide": true,
            "sAjaxSource": "{{ path('handlers_datatables') }}",

            "fnServerData": function( sUrl, aoData, fnCallback, oSettings ) {
                oSettings.jqXHR = $.ajax( {
                    "url": sUrl,
                    "data": aoData,
                    "success": fnCallback,
                    "dataType": "json",
                    "cache": false
                } );
            }
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>DataTables</h1>

        <table id="DataTables">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Country</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="3">Loading data from server</td>
                </tr>
            </tbody>
        </table>
    </section>
{% endblock %}
```