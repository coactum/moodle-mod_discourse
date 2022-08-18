# License #

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.

@copyright 2022 coactum GmbH

# DisCourse #

## Description ##

The DisCourse activity offers the possibility to conduct group discussions in a multi-step procedure.

Based on the concept of the pyramid discussion, the participants of the DisCourse initially write their own text on the discussed topic in a first phase. In the next two phases, they then combine their own positions with those of other participants and thus develop more differentiated texts together, incorporating other points of view. Finally, in the last phase, all DisCourse participants collaborate to develop a final position on the discussed topic based on their previous results.

In this way, the DisCourse enables the participants to work together to develop arguments and perspectives on a topic and to weigh them against and with each other. This allows the participants to develop differentiated positions on a complex topic in a reflexive exchange while making the progress of the discussion visible by displaying the previous preliminary positions.

On the overview page teachers can …

* See all phases and their deadlines and hints specified when the activity was created
* See a brief summary of all DisCourse groups and their submission states and open the group pages for each of these groups
* Switch phases (phases can also be switched automatically by Moodle at the specified deadline)

Students can …

* Also see all phases, their deadlines and the phase hints
* View only their own groups in the different phases and open the group pages for these

On the group page teachers can see …

* The members, the submission state and (if already handed in) the submission of the selected group
* The hint for the phase of the selected group
* The position of each group from the last phase included in this group

Students can additionally hand in a submission for the group if the phase of the group corresponds to the current phase of the DisCourse (the positions of the pre-groups are already pre-filled in the editor).

## Quick installation instructions ##

### Install from git ###
- Navigate to Moodle root folder.
- **git clone https://github.com/coactum/moodle-mod_discourse.git mod/discourse**

### Install from a compressed file ###
- Extract the compressed file data.
- Rename the main folder to discourse.
- Copy to the Moodle mod/ folder.
- Click the 'Notifications' link on the frontpage administration block.

## Dependencies ##
No dependencies.

## Incompatibilities ##
- Incompatible with 3rd party plugin block_sharing_cart (https://moodle.org/plugins/block_sharing_cart). If you try to copy a DisCourse with the Sharing-Cart it may create an incomplete and unusable DisCourse.

## Changelog ##
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
