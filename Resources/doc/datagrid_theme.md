
``` html+django
{% extends '::base.html.twig' %}

{% datagrid_theme datagrid _self %}

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

{% block datagrid_column_item__mycolumn %}
    <td style="background-color: red;">
        {{ datagrid_item(datagrid, column, item) }}
        {% for alias, action in column.actions %}
            {{ datagrid_action(datagrid, action, alias, item) }}
        {% endfor %}
    </td>
{% endblock %}
```