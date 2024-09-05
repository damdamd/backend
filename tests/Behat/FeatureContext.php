<?php

namespace Tests\Behat;

use App\App\Command\ParkVehicle;
use App\App\Command\ParkVehicleCommand;
use App\Domain\Entity\Enum\VehicleType;
use App\Domain\Entity\Location;
use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\Latitude;
use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Entity\ValueObject\Longitude;
use App\Domain\Entity\ValueObject\UserId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use App\Domain\Entity\Vehicle;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;

class FeatureContext implements Context
{
    protected UserId $otherUser;
    protected FleetId $otherUserFleetId;
    protected VehiclePlateNumber $vehiclePlateNumber;
    protected UserId $myself;
    protected FleetId $myFleetId;
    private \Faker\Generator $faker;
    private string $output;
    private LocationId $locationId;
    private Location $location;
    private string $expectedLongitude;
    private string $expectedLatitude;
    private FleetId $newFleetId;

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly VehicleRepositoryInterface  $vehicleRepository,
        private readonly FleetRepositoryInterface    $fleetRepository,
        private readonly LocationRepositoryInterface $locationRepository,
    )
    {
        $this->faker = Factory::create();
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser(): void
    {
        $this->otherUser ??= new UserId($this->faker->numberBetween());
        $this->otherUserFleetId = $this->createFleet($this->otherUser);
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet(): void
    {
        $this->iRegisterThisVehicleIntoMyFleet();

    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        exec(sprintf(
            './fleet register-vehicle %u %s',
            $this->myFleetId->toInt(),
            $this->vehiclePlateNumber
        ), $output);

        if (isset($output[0])) {
            $this->output = $output[0];
        }
    }

    /**
     * @Then I should be informed this vehicle has already been registered into my fleet
     * @throws Exception
     */
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        if (!isset($this->output) || 'Vehicle has already been registered' !== $this->output) {
            throw new Exception('No message informs that vehicle is already registered');
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     * @throws Exception
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        $myFleet = $this->fleetRepository->findOneBy([
            'fleetId' => $this->myFleetId,
        ]);
        $vehicle = $this->vehicleRepository->findOneBy([
            'vehiclePlateNumber' => $this->vehiclePlateNumber,
        ]);
        if (false === $myFleet->ownsVehicle($vehicle)) {
            throw new Exception('This vehicle is not part of my fleet.');
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        exec(sprintf(
            './fleet register-vehicle %u %s',
            $this->otherUserFleetId->toInt(),
            $this->vehiclePlateNumber
        ), $output);

        if (isset($output[0])) {
            $this->output = $output[0];
        }
    }

    /**
     * @Given /^my user$/
     */
    public function myUser(): void
    {
        $this->myself ??= new UserId($this->faker->numberBetween());
    }

    /**
     * @Given my fleet
     */
    public function myFleet(): void
    {
        $this->myUser();

        $this->myFleetId = $this->createFleet($this->myself);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle(): void
    {
        $this->vehiclePlateNumber = new VehiclePlateNumber($this->faker->word() . $this->faker->randomLetter() . $this->faker->randomLetter());
        $vehicle = new Vehicle(
            $this->vehiclePlateNumber,
            $this->faker->randomElement(VehicleType::cases())
        );
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet(): void
    {
        // Todo maybe use repository instead of command
        $this->iRegisterThisVehicleIntoMyFleet();
    }

    /**
     * @Given this vehicle is registered into other users fleet
     */
    public function iHaveRegisteredThisVehicleIntoOtherUsersFleet(): void
    {
        $otherUsersFleet = $this->fleetRepository->find($this->otherUserFleetId);
        $vehicle = $this->vehicleRepository->find($this->vehiclePlateNumber);

        $otherUsersFleet->addVehicle($vehicle);
        $this->entityManager->persist($otherUsersFleet);
    }

    /**
     * @Given a location
     */
    public function aLocation(): void
    {
        $this->expectedLongitude = $this->faker->longitude();
        $this->expectedLatitude = $this->faker->latitude();
        $location = new Location(
            new Longitude($this->expectedLongitude),
            new Latitude($this->expectedLatitude)
        );
        $this->entityManager->persist($location);
        $this->entityManager->flush();
        $this->locationId = $location->getLocationId();
    }

    /**
     * @Then the known location of my vehicle should verify this location
     * @throws Exception
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        exec(sprintf(
            './fleet localize-vehicle %u %s',
            $this->myFleetId->toInt(),
            $this->vehiclePlateNumber
        ), $output);

        $latitudeMatch = $output[0] === $this->expectedLatitude;
        $longitudeMatch = $output[1] === $this->expectedLongitude;

        if (!$latitudeMatch || !$longitudeMatch) {
            throw new Exception('My vehicle is not parked to this location');
        }
    }

    /**
     * @Given the vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        $command = new ParkVehicleCommand($this->vehiclePlateNumber, $this->locationId);
        (new ParkVehicle($this->locationRepository, $this->vehicleRepository))($command);
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation(): void
    {
        $this->iParkMyVehicleAtThisLocation();
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation(): void
    {
        $command = sprintf(
            './fleet park-vehicle %u %s',
            $this->locationId->toInt(),
            $this->vehiclePlateNumber
        );
        exec($command, $output);
        if (isset($output[0])) {
            $this->output = $output[0];
        }
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     * @throws Exception
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        if (!isset($this->output) || 'This vehicle is already parked at this location' !== $this->output) {
            throw new Exception('No message informs me that my vehicle is already parked at this location.');
        }
    }

    /**
     * todo maybe use symfony console test
     * @When /^I query my vehicle location$/
     */
    public function iQueryMyVehicleLocation(): void
    {
        exec(sprintf(
            './fleet localize-vehicle %u %s',
            $this->myFleetId->toInt(),
            $this->vehiclePlateNumber
        ), $output);

        $this->location = new Location(new Longitude($output[1]), new Latitude($output[0]));
    }

    /**
     * @AfterScenario @database
     */
    public function rollbackTransaction(): void
    {
        exec('./fleet doctrine:fixtures:load');
    }

    /**
     * @Then /^I receive the location longitude and latitude$/
     * @throws Exception
     */
    public function iReceiveTheLocationLongitudeAndLatitude(): void
    {
        if (false === isset($this->location)) {
            throw new Exception('I didn\'t receive the location longitude and latitude');
        }
        if ((string)$this->location->getLatitude() !== $this->expectedLatitude) {
            throw new Exception('This latitude is not matching the expected one');
        }
        if ((string)$this->location->getLongitude() !== $this->expectedLongitude) {
            throw new Exception('This longitude is not matching the expected one');
        }
    }

    /**
     * @When /^I try to query the vehicle location from my fleet$/
     */
    public function iTryToQueryTheVehicleLocationFromMyFleet(): void
    {
        $command = sprintf(
            './fleet localize-vehicle %u %s',
            $this->myFleetId->toInt(),
            $this->vehiclePlateNumber
        );
        exec($command, $output);
        $this->output = $output[0];

    }

    /**
     * @Then /^I should be informed that the vehicle doesn't belong to this fleet$/
     * @throws Exception
     */
    public function iShouldBeInformedThatTheVehicleDoesnTBelongToThisFleet(): void
    {
        if ('This vehicle doesn\'t belongs to this fleet' !== $this->output) {
            throw new Exception('No message informs me that the vehicle doesn\'t belong to this fleet');
        }
    }

    /**
     * @When /^I create a new fleet$/
     */
    public function iCreateANewFleet(): void
    {
        $this->newFleetId = $this->createFleet($this->myself);
    }

    /**
     * @Then /^I can retrieve this fleet from my fleet list$/
     * @throws Exception
     */
    public function iCanRetrieveThisFleetFromMyFleetList(): void
    {
        $createdFleet = $this->fleetRepository->findOneBy([
            'fleetId' => $this->newFleetId,
            'userId' => $this->myself
        ]);

        if (null === $createdFleet) {
            throw new Exception('Impossible to retrieve this fleet from my fleets');
        }
    }

    /**
     * @When /^I create a new fleet with other user$/
     */
    public function iCreateANewFleetWithOtherUser(): void
    {
        $otherUserId = new UserId($this->faker->numberBetween());
        $this->newFleetId = $this->createFleet($otherUserId);
    }

    /**
     * @Then /^I can't retrieve this fleet from my fleet list$/
     * @throws Exception
     */
    public function iCanTRetrieveThisFleetFromMyFleetList(): void
    {
        $createdFleet = $this->fleetRepository->findOneBy([
            'fleetId' => $this->newFleetId,
            'userId' => $this->myself
        ]);
        if (null !== $createdFleet) {
            throw new Exception('fleet retrieves from my fleets');
        }
    }

    private function createFleet(UserId $userId): FleetId
    {
        exec(sprintf('./fleet create %u', $userId->toInt()), $output);
        return new FleetId($output[0]);
    }
}
