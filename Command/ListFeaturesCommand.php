<?php

namespace Af\ProgressiveBundle\Command;

use Progressive\Progressive;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListFeaturesCommand extends Command
{
    private $progressive;
    protected static $defaultName = 'progressive:features';

    public function __construct(Progressive $progressive)
    {
        parent::__construct();

        $this->progressive = $progressive;
    }

    protected function configure()
    {
        $this
            ->setDescription('List the available features.')
            ->setHelp(
                <<<'EOF'
The <info>%command.name%</info> command lists all the features defined in Progressive:

    <info>php %command.full_name%</info>

You can also display the feature's configuration:

    <info>php %command.full_name% feature-name</info>
EOF
            )
            ->addArgument('feature', InputArgument::OPTIONAL, "The feature's name")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $feature = $input->getArgument('feature');

        return $feature ?
            $this->describeFeature($output, $feature) :
            $this->describeAll($output);
    }

    private function describeAll(OutputInterface $output)
    {
        $output->writeln('<comment>Available features:</comment>');

        foreach ($this->progressive->all() as $feature => $config) {
            $output->writeln(sprintf('  <info>%s</info>', $feature));
        }

        return Command::SUCCESS;
    }

    private function describeFeature(OutputInterface $output, string $feature)
    {
        $config = $this->progressive->all();

        if (!array_key_exists($feature, $config)) {
            return Command::FAILURE;
        }

        $config = $config[$feature];
        // Short syntax
        if (is_bool($config)) {
            $config = ['enabled' => $config];
        }

        $output->writeln('<comment>Name:</comment>');
        $output->writeln(sprintf('  %s%s', $feature, PHP_EOL));

        $output->writeln('<comment>Config:</comment>');

        return $this->describeConfig($output, $config);
    }

    private function describeConfig(OutputInterface $output, array $config, int $level = 1)
    {
        foreach ($config as $key => $values) {
            $output->write(sprintf('%s<info>%s:</info>', str_repeat(' ', $level * 2), $key));

            if (is_array($values)) {
                if (!is_int(key($values))) {
                    $output->writeln('');
                    $this->describeConfig($output, $values, ++$level);
                    --$level;
                    continue;
                }

                $values = implode(', ', $values);
            }

            $output->writeln(sprintf(' %s', $values));
        }

        return Command::SUCCESS;
    }
}
