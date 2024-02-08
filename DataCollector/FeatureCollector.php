<?php

namespace Af\ProgressiveBundle\DataCollector;

use Progressive\Progressive;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class FeatureCollector extends DataCollector
{
    private Progressive $progressive;
    private VarCloner $cloner;

    public function __construct(Progressive $progressive, VarCloner $cloner)
    {
        $this->progressive = $progressive;
        $this->cloner = $cloner;
    }

    public function collect(Request $request, Response $response, ?\Throwable $exception = null)
    {
        foreach ($this->progressive->all() as $name => $config) {
            $this->data[$name] = [
                'config' => $this->cloner->cloneVar($config),
                'enabled' => $this->progressive->isEnabled($name),
            ];
        }
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'progressive.features';
    }

    public function getFeatures(): array
    {
        return array_keys($this->data);
    }

    public function getConfig($name): Data
    {
        return array_key_exists($name, $this->data) ?
            $this->data[$name]['config'] :
            [];
    }

    public function isEnabled(string $name): bool
    {
        return array_key_exists($name, $this->data) ?
            $this->data[$name]['enabled'] :
            false;
    }
}
