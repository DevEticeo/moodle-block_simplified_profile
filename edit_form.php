<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block simplified_profile configuration form definition 
 *
 * @package    block_simplified_profile
 * @copyright  2022 may Eticeo <contact@eticeo.fr>
 * @author     2022 may Guevara Gabrielle <gabrielle.guevara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_simplified_profile_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $PAGE;
        /**
         *      CSS
         */
        $PAGE->requires->css(new moodle_url("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"), true);

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // restricted access by role
        $userRoleList = array(0 => get_string('everybody', BLOCK_SIMPLIFIED_PROFILE));
        $userRoles = get_all_roles();
        foreach($userRoles as $role) {
            if ($role->shortname != '') {
                $userRoleList[$role->id] = $role->shortname.($role->name != '' ? ' ('.$role->name.')' : '');
            }
        }

        $select = $mform->addElement('select', 'config_user_role', get_string('config_user_role', BLOCK_SIMPLIFIED_PROFILE), $userRoleList);
        $select->setSelected(0);
        $select->setMultiple(true);
                

        /*****************************************************
         *                  END PARAMS
         *****************************************************/

        /**
         *      JavaScript
         */
        $PAGE->requires->js(new moodle_url("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"), true);
        $PAGE->requires->js(new moodle_url("https://code.jquery.com/ui/1.13.0/jquery-ui.js"), true);
        $PAGE->requires->js(new moodle_url("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"), true);
        $PAGE->requires->js("/blocks/simplified_profile/js/edit_form.js", true);
    }
}
