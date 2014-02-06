See http://jqueryui.com/autocomplete/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function bootstrapTypeaheadAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), 'bootstrap_typeahead');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('bundles/datathekedemo/vendor/bootstrap/css/bootstrap-responsive.min.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/vendor/jquery/jquery-1.9.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/vendor/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <script>

        $('#bootstrap_typeahead').typeahead({

            source: function (query, process) {
                return $.get("{{ path('handlers_bootstraptypeahead') }}", { query: query }, function (data) {
                    return process($.map(data, function(item) { return item['name']+' ('+item['country']+')'; }));
                });
            },
            matcher: function (item) {
                return true;
            }
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>Bootstrap Typeahead</h1>

        <input type="text" id="bootstrap_typeahead" placeholder="Search..." autocomplete="off" />
    </section>
{% endblock %}
```