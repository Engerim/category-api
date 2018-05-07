# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  In order to prove that the category endpoint works correctly
  As a client
  I want to retrieve categories

  Background:
    Given I add "CONTENT_TYPE" header equal to "application/json"
    And I add "ACCEPT" header equal to "application/json"
    And the database is empty
    And the fixtures file "category.yml" is loaded
    And the entity manager is cleared

  Scenario: get all
    When I send a GET request to "/categories"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "features/schema/categories.json"

  Scenario: get one category by id
    When I send a GET request to "/categories/83a2c894-85e7-4761-9e10-88423d08d4e2"
    Then the response status code should be 200
    And the JSON node "root.isVisible" should be false
    And the JSON node "root.children" should have 2 elements
    And the JSON should be valid according to the schema "features/schema/category.json"

  Scenario: get children of a category
    When I send a GET request to "/categories/83a2c894-85e7-4761-9e10-88423d08d4e2/childrens"
    Then the response status code should be 200
    And the JSON node "root" should have 2 elements
    And the JSON should be valid according to the schema "features/schema/categories.json"

  Scenario: get one category with wrong id
    When I send a GET request to "/categories/82d473c4-02de-4a90-b9df-5aee0c5f8e53"
    Then the response status code should be 404

  Scenario: get categories by slug
    When I send a GET request to "/categories?slug=premium"
    And the response status code should be 200
    And the JSON node "root" should have 1 elements
    And the JSON should be valid according to the schema "features/schema/categories.json"
    And the JSON node "root[0].id" should be equal to "83a2c894-85e7-4761-9e10-88423d08d4e2"

  Scenario: update the visibility of a category
    When I send a PATCH request to "/categories/83a2c894-85e7-4761-9e10-88423d08d4e2" with body:
    """
      {
        "isVisible" : true
      }
    """
    And the response status code should be 200
    And the JSON should be valid according to the schema "features/schema/category.json"
    And the JSON node "root.isVisible" should be true

  Scenario: update an existing category
    When I send a PUT request to "/categories/95eda71d-0b08-429b-b9d7-17c49728df87" with body:
    """
      {
        "name": "Make Up Update",
        "slug": "make-up-update",
        "isVisible" : true
      }
    """
    And the response status code should be 200
    And the JSON should be valid according to the schema "features/schema/category.json"
    And the JSON node "root.isVisible" should be true

  Scenario: create a new category
    When I send a POST request to "/categories" with body:
    """
      {
        "name": "Hair care",
        "slug": "hair-care",
        "isVisible" : true
      }
    """
    And the response status code should be 201
    And the JSON should be valid according to the schema "features/schema/category.json"
    And the JSON node "root.isVisible" should be true

  Scenario: try to create a new category without the required fields
    When I send a POST request to "/categories" with body:
    """
      { }
    """
    And the response status code should be 400
    And the JSON node "violations" should have 5 element

  Scenario: create a new category with parent
    Given I add "CONTENT_TYPE" header equal to "application/vnd.api+json"
    And I add "ACCEPT" header equal to "application/vnd.api+json"
    When I send a "POST" request to "/categories" with body:
    """
    {
      "data": {
        "type": "category",
        "attributes": {
          "name": "Hair care",
          "slug": "hair-care",
          "isVisible" : true
        },
        "relationships": {
          "parent": {
            "data": {
              "type": "category",
              "id": "/categories/83a2c894-85e7-4761-9e10-88423d08d4e2"
            }
          }
        }
      }
    }
    """
    And the response status code should be 201
    Given I add "CONTENT_TYPE" header equal to "application/json"
    And I add "ACCEPT" header equal to "application/json"
    When I send a GET request to "/categories/83a2c894-85e7-4761-9e10-88423d08d4e2"
    And the JSON node "root.children" should have 3 elements
    And the JSON should be valid according to the schema "features/schema/category.json"

  Scenario: check eTag headers
    When I send a GET request to "/categories/95eda71d-0b08-429b-b9d7-17c49728df87"
    Then the response status code should be 200
    And the header "etag" should be equal to '"ba21505692cdb6e01301dbe36f0ef735"'
    And the header "cache-control" should be equal to 'max-age=3600, public, s-maxage=3600'
    And I add "CONTENT_TYPE" header equal to "application/json"
    And I add "ACCEPT" header equal to "application/json"
    And I send a PATCH request to "/categories/95eda71d-0b08-429b-b9d7-17c49728df87" with body:
    """
      {
        "isVisible" : true
      }
    """
    And the response status code should be 200
    And I add "CONTENT_TYPE" header equal to "application/json"
    And I add "ACCEPT" header equal to "application/json"
    And I send a GET request to "/categories/95eda71d-0b08-429b-b9d7-17c49728df87"
    And the header "etag" should be equal to '"4427f8de31dbd9598543fcf391dd001d"'