@mod @mod_dataform @dataformactivity @dataformaccess
Feature: Dataform access permissions

    @javascript
    Scenario: Early access
        Given I start afresh with dataform "Test access early"

        And I log in as "teacher1"
        And I follow "Course 1"
        And I follow "Test access early"

        ## Field
        And I go to manage dataform "fields"
        And I add a dataform field "text" with "Field Text"

        ## View
        And I go to manage dataform "views"
        And I add a dataform view "aligned" with "View Aligned"
        And I follow "Edit View Aligned"
        And I expand all fieldsets
        And I prepend "<div>Num entries total: ##numentriestotal##</div><div>Num entries max: ##numentriesviewable##</div><div>Num entries filtered: ##numentriesfiltered##</div><div>Num entries displayed: ##numentriesdisplayed##</div>" to field "View template"
        And I press "Save changes"
        And I set "View Aligned" as default view

        ## Entries
        And the following dataform "entries" exist:
            | dataform  | user          | group | timecreated   | timemodified  | Field Text                |
            | dataform1 | teacher1      |       |               |               | 1 Entry by Teacher 01     |
            | dataform1 | assistant1    |       |               |               | 2 Entry by Assistant 01   |
            | dataform1 | student1      |       |               |               | 3 Entry by Student 01     |
            | dataform1 | student2      |       |               |               | 4 Entry by Student 02     |
            | dataform1 | student3      |       |               |               | 5 Entry by Student 03     |

        Then I follow "Edit settings"
        And I expand all fieldsets
        And I set the field "id_timeavailable_enabled" to "checked"
        And I set the field "id_timeavailable_month" to "12"
        And I set the field "id_timeavailable_hour" to "23"
        And I set the field "id_timeavailable_minute" to "55"
        And I press "Save and display"


        # Teacher access

        # View
        Then I see "Num entries total: 5"
        And I see "Num entries max: 5"
        And I see "Num entries filtered: 5"
        And I see "Num entries displayed: 5"
        And I see "1 Entry by Teacher 01"
        And I see "2 Entry by Assistant 01"
        And I see "3 Entry by Student 01"
        # ... same for other entries

        # Add
        And I see "Add a new entry"

        # Update
        And "Edit Entry 1" "link" exists
        And "Edit Entry 2" "link" exists
        And "Edit Entry 3" "link" exists
        # ... same for other entries

        # Delete
        And "Delete Entry 1" "link" exists
        And "Delete Entry 2" "link" exists
        And "Delete Entry 3" "link" exists
        # ... same for other entries

        And I log out

        # Assistant access
        #---------------------------
        Given I log in as "assistant1"
        And I follow "Course 1"
        And I follow "Test access early"

        # View
        Then I see "Num entries total: 5"
        And I see "Num entries max: 5"
        And I see "Num entries filtered: 5"
        And I see "Num entries displayed: 5"
        And I see "1 Entry by Teacher 01"
        And I see "2 Entry by Assistant 01"
        And I see "3 Entry by Student 01"
        # ... same for other entries

        # Add
        And I see "Add a new entry"

        # Update
        And "Edit Entry 1" "link" exists
        And "Edit Entry 2" "link" exists
        And "Edit Entry 3" "link" exists
        # ... same for other entries

        # Delete
        And "Delete Entry 1" "link" exists
        And "Delete Entry 2" "link" exists
        And "Delete Entry 3" "link" exists
        # ... same for other entries

        And I log out

        # Student 1 access
        #---------------------------
        Given I log in as "student1"
        And I follow "Course 1"
        And I follow "Test access early"

        # View
        Then I see "Num entries total: 5"
        And I see "Num entries max: 5"
        And I see "Num entries filtered: 5"
        And I see "Num entries displayed: 5"

        And I see "1 Entry by Teacher 01"
        And I see "2 Entry by Assistant 01"
        And I see "3 Entry by Student 01"
        And I see "4 Entry by Student 02"
        # ... same for other entries

        # Add
        And I cannot add a new entry in dataform "1" view "1"

        # Update
        And I cannot edit entry "1" in dataform "1" view "1"
        And I cannot edit entry "2" in dataform "1" view "1"
        And I cannot edit entry "3" in dataform "1" view "1"
        And I cannot edit entry "4" in dataform "1" view "1"
        # ... same for other entries

        # Delete
        And I cannot delete entry "1" with content "1 Entry by Teacher 01" in dataform "1" view "1"
        And I cannot delete entry "2" with content "2 Entry by Assistant 01" in dataform "1" view "1"
        And I cannot delete entry "3" with content "3 Entry by Student 01" in dataform "1" view "1"
        And I cannot delete entry "4" with content "4 Entry by Student 02" in dataform "1" view "1"
        # ... same for other entries

