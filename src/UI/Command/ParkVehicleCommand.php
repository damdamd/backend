<?php

namespace App\UI\Command;

use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'park-vehicle', description: 'a command to park a vehicle')]
class ParkVehicleCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('locationId', InputArgument::REQUIRED, 'locationId');
        $this->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'vehiclePlateNumber');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locationId = new LocationId($input->getArgument('locationId'));
        $vehiclePlateNumber = new VehiclePlateNumber($input->getArgument('vehiclePlateNumber'));
        $command = new \App\App\Command\ParkVehicleCommand($vehiclePlateNumber, $locationId);

        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $handlerFailedException) {
            $output->write($handlerFailedException->getPrevious()->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
