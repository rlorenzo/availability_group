@availability @availability_publicprivate
Feature: availability_publicprivate
  In order to allow some content to be public and others private
  As a teacher
  I need to set conditions which prevent student access

  Background:
  # Create class, users
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username |
      | teacher |
      | enrolstudent |
      | nonenrolstudent |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher | C1     | editingteacher |
      | enrolstudent | C1     | student        |
    And the following config values are set as admin:
      | enableavailability  | 1 |
    # Login as teacher and add private content
    And I log in as "teacher"
    And I am on site homepage
    And I follow "Course 1"
    And I click on "Edit settings" "link" in the "Administration" "block"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save and display"
    And I turn editing mode on
    And I add a "Page" to section "1"
    And I set the following fields to these values:
      | Name         | Page |
      | Description  | Test   |
      | Page content | Test   |
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Public/Private" "button" in the "Add restriction..." "dialogue"

  @javascript
  Scenario: Test adding private content
    And I set the field "Content should be" to "Private"
    And I press "Save and return to course"

  # Login as enroled student and see content
    And I log out
    When I log in as "enrolstudent"
    And I am on site homepage
    And I follow "Course 1"
    Then I should see "Page"
    And I log out

  # Login as unenroled student and not see content
    When I log in as "nonenrolstudent"
    And I am on site homepage
    And I follow "Course 1"
    Then I should see "Only available to course members"
    And I should see "Topic 1"

  @javascript
  Scenario: Test adding public content
    And I set the field "Content should be" to "Public"
    And I press "Save and return to course"

  # Login as enroled student and see content
    And I log out
    When I log in as "enrolstudent"
    And I am on site homepage
    And I follow "Course 1"
    Then I should see "Page"
    And I log out

  # Login as unenroled student and see content
    When I log in as "nonenrolstudent"
    And I am on site homepage
    And I follow "Course 1"
    Then I should see "Page"
    And I should not see "Only available to course members"
    And I should see "Topic 1"
