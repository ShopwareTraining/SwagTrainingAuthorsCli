<?php declare(strict_types=1);

namespace SwagTraining\AuthorsCli\Console\Command;

use Shopware\Core\Framework\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends AbstractCommand
{
    /**
     * Configure this command
     */
    protected function configure()
    {
        $this->setName('training:authors:create')
            ->setDescription('Create a new example author')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Name')
            ->addOption('description', null, InputOption::VALUE_OPTIONAL, 'Description')
            ->addOption('birthdate', null, InputOption::VALUE_OPTIONAL, 'Birthdate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = [];
        $data['name'] = $this->getValueFromInput($input, $output, 'name');
        $data['description'] = $this->getValueFromInput($input, $output, 'description');
        $data['birthdate'] = $this->getValueFromInput($input, $output, 'birthdate');

        $context = Context::createDefaultContext();
        $this->authorRepository->upsert([$data], $context);

        $output->writeln('Create new author record');
        return 0;
    }
}
