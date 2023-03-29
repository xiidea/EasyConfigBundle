<?php

namespace Xiidea\EasyConfigBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BaseTemplateExtension extends AbstractExtension
{
    private string $templateName;

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('baseTemplate', [$this, 'getBaseTemplate']),
        ];
    }

    public function getBaseTemplate(): string
    {
        return $this->templateName;
    }
}
