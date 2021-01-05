<?php

namespace Af\ProgressiveBundle\Tests\Rule;

use Af\ProgressiveBundle\Rule\Environments;
use PHPUnit\Framework\TestCase;
use Progressive\ParameterBagInterface;

class EnvironmentsTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testDecide(string $env, array $envs, bool $expected)
    {
        $context = $this->createMock(ParameterBagInterface::class);

        $rule = new Environments($env);
        $response = $rule->decide($context, $envs);

        $this->assertSame($response, $expected);
    }

    public function valueProvider(): array
    {
        return [
            ['dev', ['dev', 'test', 'prod'], true],
            ['dev', ['preprod', 'dev'], true],
            ['test', ['test'], true],
            ['test', [], false],
            ['prod', ['dev', 'test'], false],
        ];
    }
}
