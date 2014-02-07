See http://www.dynatable.com/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function dynatableAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'dynatable');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/dynatable/jquery.dynatable.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/dynatable/jquery.dynatable.js') }}" type="text/javascript"></script>

    <script>

        $('#dynatable').dynatable({
            dataset: {
                ajax: true,
                ajaxUrl: "{{ path('handlers_dynatable') }}",
                ajaxOnLoad: true,
                records: []
            }
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>Dynatable</h1>

        <table id="dynatable">
            <thead>
                <tr>
                    <td>firstname</td>
                    <td>lastname</td>
                    <td>age</td>
                </tr>
            </thead>
        </table>
    </section>
{% endblock %}
```