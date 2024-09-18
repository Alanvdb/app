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

/* layout.twig.php */
class __TwigTemplate_bb1da2d455754c12c194f3944c2c948f extends Template
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
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield Twig\Extension\CoreExtension::include($this->env, $context, "layout/head.twig.php");
        yield "
";
        // line 2
        yield Twig\Extension\CoreExtension::include($this->env, $context, "layout/header.twig.php");
        yield "

";
        // line 4
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 6
        yield "
";
        // line 7
        yield Twig\Extension\CoreExtension::include($this->env, $context, "layout/footer.twig.php");
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout.twig.php";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  62 => 4,  57 => 7,  54 => 6,  52 => 4,  47 => 2,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout.twig.php", "F:\\Coding\\PHP\\lab\\github-repositories\\app\\assets\\templates\\layout.twig.php");
    }
}
