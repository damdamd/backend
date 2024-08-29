Feature:

  In order to localize a vehicle of a fleet
  As an application user
  I should be able to get locations latitude and longitude

  @critical
  Scenario: Successfully localize vehicle
    Given my fleet
    And a vehicle
    And a location
    And I have registered this vehicle into my fleet
    And the vehicle has been parked into this location
    When I query my vehicle location
    Then I receive the location longitude and latitude

  Scenario:
    Given the fleet of another user
    And my fleet
    And a vehicle
    And a location
    And this vehicle is registered into other users fleet
    And the vehicle has been parked into this location
    When I try to query the vehicle location from my fleet
    Then I should be informed that the vehicle doesn't belong to this fleet