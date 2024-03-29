## Changelog ##
- [1.2.3]:
    - Ensured compatibility with Moodle 4.2.
        - [Layout]: Minor layout fixes because of the new versions of the bootstrap and fontawesome libraries.
        - [Icon]: Added monologo version of the activity icon for current Moodle versions.
        - [Bugfix]: Fix for an error when a discourse group is deleted externally.
    - [Bugfix]: Hide heading and intro in Moodle 4.0 to avoid redundant information.
    - [Chore]: Some code reformating to fix moodle code check errors.

- [1.2.2]:
    - Hotfix to make recent changes for icon and layout compatible with Moodle pre 4.0.

- [1.2.1]:
    - [Bugfix]: Added some margin between current submission and return button on groupview.
    - [Bugfix]: Reduced height of the phase information area on view page if no phase hints are given.
    - [Bugfix]: Default time values for phases now at noon (Phase 1: +7 days noon, Phase 2: +14 days noon, Phase 3: +21 days noon, Phase 4: +28 days noon).
    - [Bugfix]: If a group was deleted now showing "Deleted group" on groupview for the former submissions.
    - [Improvement]: On groupview now showing if group is in active phase or if phase is already closed.
    - [Improvement]: Phase deadlines are now validated when creating or editing a discourse.
    - [Improvement]: Added hint to show teachers if the group mode "Visible groups" is activated so that participants can see all groups and submissions (and not just their own as usual).
    - [Security]: Added sesskey check for manual switching of phases.
    - [Layout]: Heading and intro are not displayed on Moodle 4.1 and above to comply with the new page layout.
    - [Icon]: Activity icon now has the purpose COLLABORATION in Moodle 4.1 and above.

- [1.2.0]:
    - [Bugfix]: Deleting expired contexts via Moodle privacy mechanism now deletes all groups and groupings of a DisCourse (the grouping wich id is stored for the instance in the table mod_discourse and its groups).
    - [Bugfix]: Deleting the whole plugin now removes all groupings connected with DisCourses (and all groups in this groupings).
    - [Bugfix]: Renaming a DisCourse (in the edit settings, not via the quick edit option in the course) now renames groups and groupings connected with the DisCourse to its new name.
    - [Bugfix]: Manual renaming of groups wont break the display of the shortened group names anymore.
    - [Bugfix]: For DisCourse groups messaging is now enabled by default.
    - [Bugfix]: If an invalid groupingid is stored for a DisCourse or the grouping stored for a DisCourse is deleted, other course groups are not displayed in the DisCourse anymore.
    - [Feature]: Added possibility to backup and restore DisCourses via Moodle backup api.
        - [Note]: If you restore a DisCourse within its original course Moodle does not create new groupings and groups. So no grouping is assigned with the new DisCourse and no participants or submissions are recreated.
        - [Note]: Incompatible with 3rd party plugin block_sharing_cart (https://moodle.org/plugins/block_sharing_cart). If you try to copy a DisCourse with the Sharing-Cart it may create an incomplete and unusable DisCourse.
    - [Feature]: DisCourses can now be included in the course reset.
    - [Versions]: Tested for Moodle 3.9, 3.10, 3.11 and 4.0
        - [Note]: Still uses old icon in Moodle 4.0
        - [Known issue]: Minor display issues with some themes in Moodle 4.0 if sidebars are open and display is too small
        - [Known issue]: After duplicating an activity in Moodle 4.0 Moodle returns the wrong activity allowed groups (for the original activity). In this case no groups but an error message is shown and the activity cant be used correctly. Teachers may just wait a few minutes, rename an activity in the course or contact the moodle administrator to clear the moodle cache to fix this issue.
