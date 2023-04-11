Overriding Default EasyConfigBundle Templates
=============================================
As you start to incorporate **EasyConfigBundle** into your application, you may need to override the default templates that are provided by the bundle. Although the template names are not configurable, Symfony provides a built-in way to [override the templates themselves](https://symfony.com/doc/current/bundles/override.html).

Example: Overriding The Default layout.html.twig
------------------------------------------------
It is highly recommended that you override the `Resources/views/layout.html.twig` template so that the pages provided by the **EasyConfigBundle** have a similar look and feel to the rest of your application. An example of overriding this layout template is depicted below using both of the overriding options listed above.

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %} Easy Config Bundle{% endblock %}</title>
    </head>
    <body>
        <div class="container">
            {% block xiidea_config_content %} {% endblock xiidea_config_content %}
        </div>
    </body>
</html>
```