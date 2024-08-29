<?php

use App\App\Command\CreateFleet;
use App\App\Command\CreateFleetCommand;
use App\App\Command\CreateLocation;
use App\App\Command\CreateLocationCommand;
use App\App\Command\CreateVehicle;
use App\App\Command\CreateVehicleCommand;
use App\App\Command\ParkVehicle;
use App\App\Command\ParkVehicleCommand;
use App\App\Command\RegisterVehicle;
use App\App\Command\RegisterVehicleCommand;
use App\App\Query\FleetList;
use App\App\Query\FleetListQuery;
use App\App\Query\LocalizeVehicle;
use App\App\Query\LocalizeVehicleQuery;
use App\Domain\Entity\Enum\VehicleType;
use App\Domain\Entity\Fleet;
use App\Domain\Entity\Location;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\Latitude;
use App\Domain\ValueObject\LocationId;
use App\Domain\ValueObject\Longitude;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\VehiclePlateNumber;
use App\Infra\Repository\FleetRepository;
use App\Infra\Repository\LocationRepository;
use App\Infra\Repository\VehicleRepository;
use Behat\Behat\Context\Context;
use Faker\Factory;

class FeatureContext implements Context
{
    protected UserId $otherUser;
    protected FleetId $otherUserFleetId;
    protected VehiclePlateNumber $vehiclePlateNumber;
    protected UserId $myself;
    protected FleetId $myFleetId;
    private \Faker\Generator $faker;
    private Exception|Throwable $exceptionCaught;
    private LocationId $locationId;
    private Throwable $exception;
    private Location $localization;
    private string $expectedLongitude;
    private string $expectedLatitude;
    private FleetId $newFleetId;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser(): void
    {
        $this->otherUser ??= UserId::create($this->faker->numberBetween());
        $this->otherUserFleetId = $this->createFleet($this->otherUser);
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet(): void
    {
        try {
            $this->iRegisterThisVehicleIntoMyFleet();
        } catch (Throwable $t) {
            $this->exceptionCaught = $t;
        }
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicleCommand(
            $this->myFleetId,
            $this->vehiclePlateNumber
        );
        $handler = (new RegisterVehicle(new FleetRepository(), new VehicleRepository()));
        $handler->__invoke($command);
    }

    /**
     * @Then I should be informed this vehicle has already been registered into my fleet
     * @throws Exception
     */
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        if (!isset($this->exceptionCaught) || 'Vehicle has already been registered' !== $this->exceptionCaught->getMessage()) {
            throw new Exception('No message informs that vehicle is already registered');
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     * @throws Exception
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        $myFleet = FleetRepository::$fleets[$this->myFleetId->toInt()];
        if (false === array_key_exists((string)$this->vehiclePlateNumber, $myFleet->getVehicles())) {
            throw new Exception('This vehicle is not part of my fleet.');
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $command = new RegisterVehicleCommand($this->otherUserFleetId, $this->vehiclePlateNumber);
        (new RegisterVehicle(new FleetRepository(), new VehicleRepository()))($command);
    }

    /**
     * @Then /^I should be informed that I'm not owning this fleet$/
     * @throws Exception
     */
    public function iShouldBeInformedThatIMNotOwningThisFleet(): void
    {
        if (false === isset($this->exceptionCaught) || 'You aren\'t the owners fleet' !== $this->exceptionCaught->getMessage()) {
            throw new Exception('No message informs that I am not owning this fleet');
        }
    }

    /**
     * @When /^I try to register this vehicle into another users fleet$/
     */
    public function iTryToRegisterThisVehicleIntoAnotherUsersFleet(): void
    {
        $this->myUser();
        try {
            $command = new RegisterVehicleCommand(
                $this->otherUserFleetId,
                $this->vehiclePlateNumber
            );
            $handler = (new RegisterVehicle(new FleetRepository(), new VehicleRepository()));
            $handler->__invoke($command);
        } catch (Throwable $exception) {
            $this->exceptionCaught = $exception;
        }
    }

    /**
     * @Given /^my user$/
     */
    public function myUser(): void
    {
        $this->myself ??= UserId::create($this->faker->numberBetween());
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
        $this->vehiclePlateNumber = VehiclePlateNumber::create($this->faker->word());
        $command = new CreateVehicleCommand($this->vehiclePlateNumber, $this->faker->randomElement(VehicleType::cases()));
        (new CreateVehicle(new VehicleRepository()))($command);
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet(): void
    {
        $this->iRegisterThisVehicleIntoMyFleet();
    }

    /**
     * @Given this vehicle is registered into other users fleet
     */
    public function iHaveRegisteredThisVehicleIntoOtherUsersFleet(): void
    {
        $fleet = FleetRepository::$fleets[$this->otherUserFleetId->toInt()] ?? throw new RuntimeException('Other user fleet is not registered');
        $myVehicle = VehicleRepository::$vehicles[(string)$this->vehiclePlateNumber] ?? throw new RuntimeException('Vehicle is not registered');
        $fleet->addVehicle($myVehicle);
        FleetRepository::$fleets[$fleet->getFleetId()->toInt()] = $fleet;
    }

    /**
     * @Given a location
     */
    public function aLocation(): void
    {
        $this->expectedLongitude = $this->faker->longitude();
        $this->expectedLatitude = $this->faker->latitude();
        $command = new CreateLocationCommand(
            new Longitude($this->expectedLongitude),
            new Latitude($this->expectedLatitude)
        );
        $this->locationId = (new CreateLocation(new LocationRepository()))($command);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     * @throws Exception
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $query = new LocalizeVehicleQuery($this->myFleetId, $this->vehiclePlateNumber);
        $foundLocation = (new LocalizeVehicle(new VehicleRepository(), new FleetRepository()))($query);

        if (false === $foundLocation->getLocationId()->equals($this->locationId)) {
            throw new Exception('My vehicle is not parked to this location');
        }
    }

    /**
     * @Given the vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        $command = new ParkVehicleCommand($this->vehiclePlateNumber, $this->locationId);
        (new ParkVehicle(new LocationRepository(), new VehicleRepository()))($command);
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation(): void
    {
        try {
            $this->iParkMyVehicleAtThisLocation();
        } catch (Throwable $t) {
            $this->exception = $t;
        }
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation(): void
    {
        $command = new ParkVehicleCommand($this->vehiclePlateNumber, $this->locationId);
        (new ParkVehicle(new LocationRepository(), new VehicleRepository()))->__invoke($command);
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     * @throws Exception
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        if (!isset($this->exception) || 'This vehicle is already parked at this location' !== $this->exception->getMessage()) {
            throw new Exception('No message informs me that my vehicle is already parked at this location.');
        }
    }

    /**
     * @When /^I query my vehicle location$/
     */
    public function iQueryMyVehicleLocation(): void
    {
        $query = new LocalizeVehicleQuery($this->myFleetId, $this->vehiclePlateNumber);
        $this->localization = (new LocalizeVehicle(new VehicleRepository(), new FleetRepository()))($query);
    }

    /**
     * @Then /^I receive the location longitude and latitude$/
     * @throws Exception
     */
    public function iReceiveTheLocationLongitudeAndLatitude(): void
    {
        if (false === isset($this->localization)) {
            throw new Exception('I didn\'t receive the location longitude and latitude');
        }
        if ((string)$this->localization->getLatitude() !== $this->expectedLatitude) {
            throw new Exception('This latitude is not matching the expected one');
        }
        if ((string)$this->localization->getLongitude() !== $this->expectedLongitude) {
            throw new Exception('This longitude is not matching the expected one');
        }
    }

    /**
     * @When /^I try to query the vehicle location from my fleet$/
     */
    public function iTryToQueryTheVehicleLocationFromMyFleet(): void
    {
        $command = new LocalizeVehicleQuery($this->myFleetId, $this->vehiclePlateNumber);
        try {
            (new LocalizeVehicle(new VehicleRepository(), new FleetRepository()))($command);
        } catch (Throwable $exception) {
            $this->exceptionCaught = $exception;
        }
    }

    /**
     * @Then /^I should be informed that the vehicle doesn't belong to this fleet$/
     * @throws Exception
     */
    public function iShouldBeInformedThatTheVehicleDoesnTBelongToThisFleet(): void
    {
        if (false === isset($this->exceptionCaught) || 'This vehicle doesn\'t belongs to this fleet' !== $this->exceptionCaught->getMessage()) [
            throw new Exception('No message informs me that the vehicle doesn\'t belong to this fleet')];
    }

    /**
     * @When /^I create a new fleet$/
     */
    public function iCreateANewFleet(): void
    {
        $command = new CreateFleetCommand($this->myself);
        $this->newFleetId = (new CreateFleet(new FleetRepository()))($command);
    }

    /**
     * @Then /^I can retrieve this fleet from my fleet list$/
     * @throws Exception
     */
    public function iCanRetrieveThisFleetFromMyFleetList(): void
    {
        $query = new FleetListQuery($this->myself);
        $myFleetList = (new FleetList(new FleetRepository()))($query);

        $result = array_filter($myFleetList, fn(Fleet $value) => $value->getFleetId()->equals($this->newFleetId));

        if (1 !== count($result)) {
            throw new Exception('Impossible to retrieve this fleet from my fleets');
        }
    }

    /**
     * @When /^I create a new fleet with other user$/
     */
    public function iCreateANewFleetWithOtherUser(): void
    {
        $otherUserId = UserId::create($this->faker->numberBetween());
        $this->createFleet($otherUserId);
    }

    /**
     * @Then /^I can't retrieve this fleet from my fleet list$/
     * @throws Exception
     */
    public function iCanTRetrieveThisFleetFromMyFleetList(): void
    {
        $query = new FleetListQuery($this->myself);
        $myFleetList = (new FleetList(new FleetRepository()))($query);

        foreach ($myFleetList as $fleet) {
            if ($fleet->getFleetId()->equals($this->newFleetId)) {
                throw new Exception('Other users fleet is part of my fleet list');
            }
        }
    }

    /**
     * @When /^I create a new location$/
     */
    public function iCreateANewLocation(): void
    {
        $command = new CreateLocationCommand(
            new Longitude($this->expectedLongitude),
            new Latitude($this->expectedLatitude)
        );
        $this->locationId = (new CreateLocation(new LocationRepository()))($command);
    }

    private function createFleet(UserId $userId): FleetId
    {
        $command = new CreateFleetCommand($userId);
        return (new CreateFleet(new FleetRepository()))($command);
    }
}
