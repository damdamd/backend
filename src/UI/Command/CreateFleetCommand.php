<?php

namespace App\UI\Command;

use App\Domain\Entity\ValueObject\UserId;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'create', description: 'a command to create a fleet')]
class CreateFleetCommand extends Command
{
    use HandleTrait;

    public function __construct(private readonly MessageBusInterface $commandBus)
    {
        $this->messageBus = $this->commandBus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'userId');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = new UserId($input->getArgument('userId'));

        $fleetId = $this->handle(new \App\App\Command\CreateFleetCommand($userId));

        $output->writeln((string) $fleetId->toInt());

        return Command::SUCCESS;
    }
}
