@mod @mod_dataform @dataformfield @dataformfield_url
Feature: Common

    @javascript
    Scenario: Manage field
        Given I run dataform scenario "manage field" with:
            | fieldtype | url |
