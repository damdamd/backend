<?php

namespace App\App\Command;

use App\Domain\Entity\Fleet;
use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Repository\FleetRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateFleet
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    public function __invoke(CreateFleetCommand $command): FleetId
    {
        $fleet = new Fleet($command->userId);

        $this->fleetRepository->create($fleet);

        return $fleet->getFleetId();
    }
}