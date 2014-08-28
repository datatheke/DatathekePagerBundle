See http://www.jquery-bootgrid.com/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function jQueryBootgridAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'jquery_bootgrid');

        return $datagrid->handleRequest($request);
    }

```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('bundles/datathekedemo/vendor/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('bundles/datathekedemo/vendor/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery-bootgrid/jquery.bootgrid.min.js') }}" type="text/javascript"></script>

    <script>

        $("#jquery-bootgrid").bootgrid({
            ajax: true,
            url: "{{ path('handlers_jquerybootgrid') }}"
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>jQuery Bootgrid</h1>

        <table id="jquery-bootgrid" class="table table-condensed table-hover table-striped">
            <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">ID</th>
                    <th data-column-id="name">Name</th>
                    <th data-column-id="country">Country</th>
                </tr>
            </thead>
        </table>
    </section>
{% endblock %}
```