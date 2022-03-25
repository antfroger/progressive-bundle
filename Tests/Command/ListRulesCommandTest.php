<?php

namespace Af\ProgressiveBundle\Tests\Command;

use Af\ProgressiveBundle\Command\ListRulesCommand;
use PHPUnit\Framework\TestCase;
use Progressive\Rule\Store;
use Symfony\Component\Console\Tester\CommandTester;

class ListRulesCommandTest extends TestCase
{
    private $tester;

    protected function setUp(): void
    {
        $store = new Store();

        $command = new ListRulesCommand($store);
        $command->setName('progressive:rules');

        $this->tester = new CommandTester($command);
    }

    public function testListRules(): void
    {
        $this->tester->execute([]);

        $this->assertSame(
            <<<EOF
Available rules:
  enabled
  partial
  unanimous

EOF,
            $this->tester->getDisplay()
        );
    }
}
