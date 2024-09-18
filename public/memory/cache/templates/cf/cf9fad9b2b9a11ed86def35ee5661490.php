<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layout/head.twig.php */
class __TwigTemplate_267bc01be61c5fbb2352f69403c6939e extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<?php
/**
 * htmlHead.template.php
 * 
 * All params are optional.
 * 
 * @param string   \$title           The title of the page
 * @param string   \$favicon         URL to the favicon
 * @param string   \$author          Author of the page
 * @param string   \$description     A brief description of the page for meta description
 * @param string   \$keywords        Keywords for meta keywords
 * 
 * @param string   \$language        Language of the page
 * @param string   \$charset         The character encoding of the page
 * @param string   \$viewport        The viewport settings
 * @param string   \$canonicalUrl    The canonical URL of the page
 * @param string[] \$metaTags        An array of additional meta tags (name => content)
 * 
 * @param string[] \$stylesheets     An array of URLs to CSS stylesheets
 * @param string   \$styleTag        Styles written in HTML document
 * @param string[] \$scripts         An array of URLs to JavaScript files
 */
\$defaultTemplateValues = [
    'title' => 'Untitled Document',
    'favicon' => null,
    'author' => null,
    'description' => null,
    'keywords' => null,

    'language' => 'en',
    'charset' => 'UTF-8',
    'viewport' => 'width=device-width, initial-scale=1.0',
    'canonicalUrl' => null,
    'metaTags' => [],
    
    'stylesheets' => [],
    'styleTag' => null,
    'scripts' => []
];

foreach (\$defaultTemplateValues as \$var => \$value) {
    if (is_string(\$value) || is_null(\$value)) {
        \$\$var = isset(\$\$var) && is_string(\$\$var) ? htmlspecialchars(\$\$var) : htmlspecialchars(\$value);
    } elseif (is_array(\$value) && (!isset(\$\$var) || !is_array(\$\$var))) {
        \$\$var = array_map('htmlspecialchars', \$value);
    } elseif (is_array(\$value) && isset(\$\$var)) {
        foreach (\$\$var as \$index => \$providedValue) {
            if (is_string(\$providedValue)) {
                \$\$var[\$index] = htmlspecialchars(\$providedValue);
            } else {
                \$\$var = array_map('htmlspecialchars', \$value);
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang=\"<?= \$language ?>\">
<head>
    <meta charset=\"<?= \$charset ?>\">
    <meta name=\"viewport\" content=\"<?= \$viewport ?>\">
    <title><?= \$title ?></title>

    <?php if (\$author !== null): ?>
        <meta name=\"author\" content=\"<?= \$author ?>\">
    <?php endif; ?>

    <?php if (\$description !== null): ?>
        <meta name=\"description\" content=\"<?= \$description ?>\">
    <?php endif; ?>

    <?php if (\$keywords !== null): ?>
        <meta name=\"keywords\" content=\"<?= \$keywords ?>\">
    <?php endif; ?>

    <?php if (\$canonicalUrl !== null): ?>
        <link rel=\"canonical\" href=\"<?= \$canonicalUrl ?>\">
    <?php endif; ?>

    <?php if (\$favicon !== null): ?>
        <link rel=\"icon\" href=\"<?= \$favicon ?>\" type=\"image/x-icon\">
    <?php endif; ?>

    <?php foreach (\$stylesheets as \$stylesheet): ?>
        <link rel=\"stylesheet\" href=\"<?= \$stylesheet ?>\">
    <?php endforeach; ?>

    <?php foreach (\$metaTags as \$name => \$content): ?>
        <meta name=\"<?= \$name ?>\" content=\"<?= \$content ?>\">
    <?php endforeach; ?>

    <?php foreach (\$scripts as \$script): ?>
        <script src=\"<?= \$script ?>\" defer></script>
    <?php endforeach; ?>

    <?php if (\$styleTag !== null): ?>
        <style>
            <?= \$styleTag ?>
        </style>
    <?php endif; ?>
</head>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/head.twig.php";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout/head.twig.php", "F:\\Coding\\PHP\\lab\\github-repositories\\app\\assets\\templates\\layout\\head.twig.php");
    }
}
