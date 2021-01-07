<?php

namespace Af\ProgressiveBundle\Twig;

use Progressive\Progressive;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ProgressiveExtension extends AbstractExtension
{
    /** @var Progressive */
    private $progressive;

    public function __construct(Progressive $progressive)
    {
        $this->progressive = $progressive;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_enabled', [$this, 'isEnabled']),
        ];
    }

    public function isEnabled(string $feature): bool
    {
        return $this->progressive->isEnabled($feature);
    }
}
