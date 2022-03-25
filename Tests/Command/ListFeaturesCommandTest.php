<?php

namespace Af\ProgressiveBundle\Tests\Command;

use Af\ProgressiveBundle\Command\ListFeaturesCommand;
use PHPUnit\Framework\TestCase;
use Progressive\Progressive;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ListFeaturesCommandTest extends TestCase
{
    private $tester;

    protected function setUp(): void
    {
        $config = require __DIR__.'/../feature-flag.php';
        $progressive = new Progressive($config);

        $command = new ListFeaturesCommand($progressive);
        $command->setName('progressive:features');

        $this->tester = new CommandTester($command);
    }

    public function testListFeatures(): void
    {
        $this->tester->execute([]);

        $this->assertSame(
            <<<EOF
Available features:
  dark-theme
  light-theme
  homepage-v2
  strategy-unanimous
  strategy-partial

EOF,
            $this->tester->getDisplay()
        );
    }

    /**
     * @dataProvider featureResultProvider
     */
    public function testListFeature(string $name, string $expected): void
    {
        $this->tester->execute(['feature' => $name]);
        $this->assertSame($expected, $this->tester->getDisplay());
    }

    public function testFeatureDoesNotExist(): void
    {
        $this->tester->execute(['feature' => 'undefined-feature']);
        $this->assertSame(Command::FAILURE, $this->tester->getStatusCode());
    }

    public function featureResultProvider(): array
    {
        return [
            [
                'dark-theme',
                <<<EOF
Name:
  dark-theme

Config:
  enabled: 1

EOF
            ],
            [
                'light-theme',
                <<<EOF
Name:
  light-theme

Config:
  enabled: 1

EOF
            ],
            [
                'homepage-v2',
                <<<EOF
Name:
  homepage-v2

Config:
  roles: ROLE-DEV

EOF
            ],
            [
                'strategy-unanimous',
                <<<EOF
Name:
  strategy-unanimous

Config:
  unanimous:
    env: dev, preprod
    between-hours:
      start: 9
      end: 18

EOF
            ],
            [
                'strategy-partial',
                <<<EOF
Name:
  strategy-partial

Config:
  partial:
    env: dev, preprod
    roles: ROLE_ADMIN
    between-hours:
      start: 9
      end: 18

EOF
            ],
        ];
    }
}
