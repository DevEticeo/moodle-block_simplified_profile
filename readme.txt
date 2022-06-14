==================================================
                LICENSE
==================================================
2022 module for Moodle 

Copyright (C) 2022 Eticeo

Authors: Guevara Gabrielle

This plugin is a Moodle module - http://moodle.org/

Moodle is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Moodle is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

http: www.gnu.org/copyleft/gpl.html

==================================================
                DESCRIPTION
==================================================

Allows to have a simplified profile block with the user last name, first name and email address , and the posibility to change his/her password.

The block is composed by a first line with the primary informations (last name / first name / email address of the current user) and by a second line to change the password.
Three fields make up this part, the user must enter his current correct password on one of the fields, a password that respects the moodle password rules on another, and the confirmation of this new password on the last one.
This information will be checked by api, if incorrect a relative error message will be displayed under the problematic field, if correct, the password will be changed and a success message will be displayed.
Everything is done by ajax to allow the page not to regenerate.

==================================================
                INSTALLATION
==================================================

1.  Download this ZIP file
2.  Log in to your Moodle site as administrator and go to Administration > Site administration > Plugins > Install plugins
3.  Upload the ZIP on the file zone 
4.  If your target directory is not writable, you will see a warning message.
5.  On the next screen, you will then see an acknowledgement message stating that you take responsibility for installing the plugin.

