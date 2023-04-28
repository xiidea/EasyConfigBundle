# Step 9: Overriding default EasyConfigBundle templates (optional)
==================================================================
As you start to incorporate **EasyConfigBundle** into your application, you may need to override the default templates that are provided by the bundle. Although the template names are not configurable, Symfony provides a built-in way to [override the templates themselves](https://symfony.com/doc/current/bundles/override.html).

Example: Overriding The Default layout.html.twig
------------------------------------------------
It is highly recommended that you override the `Resources/views/layout.html.twig` template so that the pages provided by the **EasyConfigBundle** have a similar look and feel to the rest of your application. An example of overriding this layout template is depicted below using both of the overriding options listed above.

Here is the default `layout.html.twig` provided by the **EasyConfigBundle**:
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
As you can see this is very basic and does not really have much structure, so you could replace it with a layout file that is appropriate for your application. The notable thing in this template is the block named `xiidea_config_content`. This is the block where the content from each of the different bundle's actions will be displayed, so you must make sure to include this block in the layout file you will use to override the default one.

The following Twig template file is an example of a layout file that might be used to override the one provided by the bundle.

```html
{% block title %}Your Application{% endblock %}

{% block content %}
    {% block xiidea_config_content %}{% endblock %}
{% endblock %}
```
The above example extends the layout template from the layout of your application. The main content of each page is displayed in the `content` block. For this reason, the `xiidea_config_content` block has been positioned within it. This approach will achieve the intended outcome of integrating the output from **EasyConfigBundle** actions into the layout of our application, maintaining its visual identity.

To override the layout template located at `Resources/views/layout.html.twig` in the `XiideaEasyConfigBundle` directory, you could place your new layout template at `templates/bundles/XiideaEasyConfigBundle/layout.html.twig`.

As you can see the pattern for overriding templates in this way is to create a folder with the name of the bundle class in the `templates/bundles/` directory. Then add your new template to this folder, keeping the directory structure from the original bundle.

Note: Do not forget to clear the cache after overriding a template, otherwise It may not reflect the changing effect.