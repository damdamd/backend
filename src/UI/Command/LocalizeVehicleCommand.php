<?php

namespace App\UI\Command;

use App\App\Query\LocalizeVehicleQuery;
use App\Domain\Entity\Location;
use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'localize-vehicle', description: 'a command to localize a vehicle')]
class LocalizeVehicleCommand extends Command
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'fleetId');
        $this->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'vehiclePlateNumber');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fleetId = new FleetId($input->getArgument('fleetId'));
        $vehiclePlateNumber = new VehiclePlateNumber($input->getArgument('vehiclePlateNumber'));

        $query = new LocalizeVehicleQuery($fleetId, $vehiclePlateNumber);
        try {
            /** @var Location $location */
            $location = $this->handle($query);
        } catch (HandlerFailedException $handlerFailedException) {
            $output->write($handlerFailedException->getPrevious()->getMessage());
            return Command::FAILURE;
        }

        $output->writeln($location->getLatitude());
        $output->writeln($location->getLongitude());

        return Command::SUCCESS;
    }
}