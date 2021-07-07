<?php declare(strict_types=1);

namespace SwagTraining\AuthorsCli\Console\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('training:authors:list')
            ->setDescription('Show a listing of all example authors');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Name', 'Description', 'Birthdate']);

        foreach ($this->getAuthors() as $author) {
            $table->addRow([
                $author->getId(),
                $author->getName(),
                $author->getDescription(),
                $author->getBirthdate(),
            ]);
        }

        $table->render();
        return 0;
    }

    /**
     * @return EntityCollection
     */
    private function getAuthors(): EntityCollection
    {
        $criteria = new Criteria;
        $context = Context::createDefaultContext();
        $searchResult = $this->authorRepository->search($criteria, $context);
        return $searchResult->getEntities();
    }
}
