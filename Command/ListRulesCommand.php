<?php

namespace Af\ProgressiveBundle\Command;

use Progressive\Rule\RuleInterface;
use Progressive\Rule\StoreInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRulesCommand extends Command
{
    private StoreInterface $store;
    protected static $defaultName = 'progressive:rules';

    public function __construct(StoreInterface $store)
    {
        parent::__construct();

        $this->store = $store;
    }

    protected function configure()
    {
        $this
            ->setDescription('List the available rules.')
            ->setHelp(
                <<<'EOF'
The <info>%command.name%</info> command lists all the rules provided by Progressive:

    <info>php %command.full_name%</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rules = $this->store->list();

        if (empty($rules)) {
            $output->writeln('<error>No available rules.</error>');

            return Command::SUCCESS;
        }

        usort($rules, function (RuleInterface $a, RuleInterface $b) {
            return strnatcmp($a->getName(), $b->getName());
        });

        $output->writeln('<comment>Available rules:</comment>');

        foreach ($rules as $rule) {
            $output->writeln(sprintf('  <info>%s</info>', $rule->getName()));
        }

        return Command::SUCCESS;
    }
}
