<?php declare(strict_types=1);

namespace SwagTraining\AuthorsCli\Console\Command;

use InvalidArgumentException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

abstract class AbstractCommand extends Command
{
    /**
     * @var EntityRepositoryInterface
     */
    protected EntityRepositoryInterface $authorRepository;

    /**
     * CreateCommand constructor.
     * @param EntityRepositoryInterface $authorRepository
     * @param string|null $name
     */
    public function __construct(
        EntityRepositoryInterface $authorRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function getIdFromInput(InputInterface $input, OutputInterface $output): string
    {
        $id = (string)$input->getOption('id');
        if ($id) {
            return $id;
        }

        $options = [];
        $searchResult = $this->authorRepository->search(new Criteria(), Context::createDefaultContext());
        foreach ($searchResult->getEntities() as $author) {
            $options[$author->getId()] = $author->getId() . ', ' . $author->getName();
        }

        $question = new ChoiceQuestion(
            'Which record would you like to update',
            $options,
            0
        );
        $question->setErrorMessage('Invalid ID.');

        $helper = $this->getHelper('question');
        return (string)$helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $optionName
     * @return string
     */
    protected function getValueFromInput(InputInterface $input, OutputInterface $output, string $optionName): string
    {
        $optionValue = (string)$input->getOption($optionName);
        if (empty($optionValue)) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter a ' . $optionName . ': ');
            $optionValue = $helper->ask($input, $output, $question);
        }

        if (empty($optionValue)) {
            throw new InvalidArgumentException('No value specified for ' . $optionName);
        }

        return $optionValue;
    }
}
