See http://jqueryui.com/autocomplete/

``` php
<?php
    /* ... */

use Symfony\Component\HttpFoundation\Request;

    /* ... */

    public function aucompleteAction(Request $request)
    {
        $datagrid = $this->get('datatheke.datagrid')->createHttpDataGrid('MyBundle:MyEntity', array(), null, 'autocomplete');

        return $datagrid->handleRequest($request);
    }
```

``` html+django

{% extends '::base.html.twig' %}

{% block stylesheets %}
    {{ parent() }}

    <link href="{{ asset('bundles/datathekedemo/lib/jquery-ui/themes/base/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    <script src="{{ asset('bundles/datathekedemo/lib/jqgrid/js/jquery-1.9.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/datathekedemo/lib/jquery-ui/ui/jquery-ui.js') }}" type="text/javascript"></script>

    <script>

        $('#autocomplete').autocomplete({
            minLength: 0,
            autoFocus: true,

            source: function (request, response) {
                $.ajax({
                    url: "{{ path('handlers_autocomplete') }}",
                    dataType: "json",
                    data: {'term': request.term},
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item['name']+' ('+item['country']+')',
                                value: item['id']
                            }
                        }))
                    }
                })
            },
        });

    </script>
{% endblock %}

{% block body %}
    <section>
        <h1>Autocomplete</h1>

        <input type="text" id="autocomplete" placeholder="Search..." autocomplete="off" />
    </section>
{% endblock %}
```