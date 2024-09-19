<!DOCTYPE html>
<html lang="{{ documentLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block documentTitle %}Untitled document{% endblock %}</title>

    <meta name="author" content="{% block documentAuthor %}{% endblock %}">
    <meta name="description" content="{% block documentDescription %}{% endblock %}">
    <meta name="keywords" content="{% block documentKeywords %}{% endblock %}">
    <link rel="icon" href="{{ favicon }}" type="image/x-icon">

    {% block stylesheets %}
        <link rel="stylesheet" href="/css/main.css">
    {% endblock %}

    {% block scripts %}{% endblock %}

    <style>{% block styleTagContent %}{% endblock %}</style>
</head>
<body class="darkMode">
    <header class="header">
        <p class="header-logo"><a href="<?= $uriGenerator->generateUri('home') ?>">MyBlog</a></p>
    </header>
        {% block content %}
        {% endblock %}
</body>
</html>