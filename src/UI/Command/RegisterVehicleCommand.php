<?php

namespace App\UI\Command;

use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'register-vehicle', description: 'a command to register a vehicle to a fleet')]
class RegisterVehicleCommand extends Command
{

    public function __construct(private readonly MessageBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'fleetId');
        $this->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'vehiclePlateNumber');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fleetId = new FleetId($input->getArgument('fleetId'));
        $vehiclePlateNumber = new VehiclePlateNumber($input->getArgument('vehiclePlateNumber'));

        try {
            $this->commandBus->dispatch(new \App\App\Command\RegisterVehicleCommand($fleetId, $vehiclePlateNumber));
        } catch (HandlerFailedException $handlerFailedException) {
            $output->write($handlerFailedException->getPrevious()->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}