Feature:

  In order to create a fleet
  As an application user
  I should be able to create a fleet

  @critical
  Scenario:
    Given my user
    When I create a new fleet
    Then I can retrieve this fleet from my fleet list

  Scenario:
    Given my user
    When I create a new fleet with other user
    Then I can't retrieve this fleet from my fleet list