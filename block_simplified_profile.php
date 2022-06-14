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
 * Block simplified_profile
 *
 * @package    block_simplified_profile
 * @copyright  2022 may Eticeo <contact@eticeo.fr>
 * @author     2022 may Guevara Gabrielle <gabrielle.guevara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
define('BLOCK_SIMPLIFIED_PROFILE', 'block_simplified_profile');

class block_simplified_profile extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', BLOCK_SIMPLIFIED_PROFILE); // définit le titre du bloc
    }

    /**
     * Hide or display the header
     * @return boolean
     */
    function hide_header()
    {
        return false;
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats()
    {

        return array(
            'all' => true
        );
    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple()
    {
        return true;
    }

    public function has_config()
    {
        return true;
    }

    function html_attributes()
    {
        $attributes = array(
            'id' => 'inst' . $this->instance->id,
            'class' => 'block_' . $this->name() . ' block ',
            'role' => $this->get_aria_role()
        );
        if ($this->hide_header()) {
            $attributes['class'] .= ' no-header';
        }
        if ($this->instance_can_be_docked() && get_user_preferences('docked_block_instance_' . $this->instance->id, 0)) {
            $attributes['class'] .= ' dock_on_load';
        }
        return $attributes;
    }

    /**
     * Return the userid, from userReplace or $USER
     */
    private function get_user_to_show()
    {
        global $USER;

        $userReplace = optional_param('userReplace', null, PARAM_INT);
        if ($userReplace && $userReplace != 0 && $this->is_manager($USER->id)) {
            $userid = $userReplace;
        } else {
            $userid = $USER->id;
        }

        return $userid;
    }

    /**
     * Build the block content.
     */
    public function get_content()
    {
        global $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;

        $userid = $this->get_user_to_show();
        $this->title = !empty($this->config->title) ? $this->config->title : get_string('title', BLOCK_SIMPLIFIED_PROFILE);

        // END USER
        if ($this->is_user_enabled($userid)) {
            $this->content->text = $this->get_user_data();
            
            $this->page->requires->js('/blocks/simplified_profile/js/simplified_profile.js', true);
        } else {
            $this->title = $this->title . ' <i>' . get_string('hidden_for_user', 'block_simplified_profile') . '</i>';
        }

        return $this->content;
    }
    
    /**
     * Create or update a student in the DB and register him/her in the courses
     * @param $userId                   | int user id 
     * @param $currentPassword          | string current password of the user
     * @param $newPassword              | string new password
     * @param $newPasswordConfirm       | string new password to confirm
     *
     * @return
     */
    public function update_student($userId, $currentPassword, $newPassword, $newPasswordConfirm)
    {
        global $CFG, $USER;
        
        require_once($CFG->dirroot.'/user/lib.php');
        require_once($CFG->dirroot.'/cohort/lib.php');
        
        if ($userId != $USER->id && !$this->is_manager($USER->id)) {
            $userId = $USER->id;
        }
        $user = core_user::get_user($userId);
        
        $currentPasswordOk = authenticate_user_login($user->username, $currentPassword);
        
        if (!$currentPasswordOk) {
            
            return array('error' => get_string('current_password_wrong', 'block_simplified_profile'),
                         'error_location' => 'current_password');
        }
        
        $error = '';
        if (!check_password_policy($newPassword, $error)) {
            
            return array('error' => $error,
                         'error_location' => 'new_password');
        }
        if ($newPassword != $newPasswordConfirm) {
            
            return array('error' => get_string('new_password_wrong', 'block_simplified_profile'),
                         'error_location' => 'new_password_confirm');
        }
        
        $message = null;
        
        $userid = update_internal_user_password($user, $newPassword);
        if ($userid) {
            $message = get_string('success_update_message', 'block_simplified_profile');
        }
        
        return array('userid' => $userid, 'successMessage' => $message);
    }

    /******************************************************************************************************************************************************************************************************
     *                                                                                   DISPLAY FUNCTIONS
     ******************************************************************************************************************************************************************************************************/
    
    
    private function get_user_data($userId = null)
    {
        global $USER, $CFG;
        if (!$userId) {
            $userId = $USER->id;
        }
        $user = core_user::get_user($userId);
        
        $block = '<form class="row simplified_profile_row" action="javascript:simplifiedProfile_updateUser('.$this->instance->id.')">';
        // NAMES
        $block .= '<div class="col-6">
                        <b>'.get_string('names', 'block_simplified_profile').'</b><br>
                        '.$user->firstname.' '.$user->lastname.'
                    </div>';
        
        // MAIL
        $block .= '<div class="col-6">
                        <b>'.get_string('mail', 'block_simplified_profile').'</b><br>
                        '.$user->email.'
                    </div>';
                    
        // PASSWORD
        $block .= '<div class="col-12">
                        <b>'.get_string('edit_password', 'block_simplified_profile').'</b><br><br>
                        <input type="password" placeholder="'.get_string('current_password', 'block_simplified_profile').'" name="current_password" autocomplete="off">
                        <span class="error_message current_password"></span>
                        <hr>
                        <input type="password" placeholder="'.get_string('new_password', 'block_simplified_profile').'" name="new_password" autocomplete="off">
                        <span class="error_message new_password"></span>
                        <br><br>
                        <input type="password" placeholder="'.get_string('new_password_confirm', 'block_simplified_profile').'" name="new_password_confirm" autocomplete="off">
                        <span class="error_message new_password_confirm"></span>
                    </div>';
                    
        // SUCCESS MESSAGE
        $block .= '<div class="col-12 success-message"></div>';
                    
        // BUTTONS
        $block .= '<div class="col-12 col-buttons">
                        <button type="submit" class="btn_simplified_profile">'.get_string('edit_info', 'block_simplified_profile').'</button>  
                        <button type="button" class="btn_simplified_profile" onclick="simplifiedProfile_clearAll('.$this->instance->id.')">'.get_string('cancel', 'block_simplified_profile').'</button>
                    </div>';
        
        // LOGOUT
        $block .= '<div class="col-12 col-buttons">
                        <a href="'.$CFG->wwwroot.'/login/logout.php" class="btn_simplified_profile" onclick="simplifiedProfile_logout()">'.get_string('logout', 'block_simplified_profile').'</a>
                    </div>';
        
        $block .= '</form>';
        
        
        return $block;
    }
    
    /******************************************************************************************************************************************************************************************************
     *                                                                                   ACCESS FUNCTIONS
     ******************************************************************************************************************************************************************************************************/
     
    /**
     * Return true if the user has the right to see this block
     * @param $userId | id of the selected user
     *
     * @return bool
     */
    private function is_user_enabled($userId)
    {
        global $DB, $CFG;

        $hasEnableRole = false;
        $user_roles = isset($this->config->user_role) ? $this->config->user_role : null;
        if (!empty($user_roles)) {
            //enabled for everybody
            if (in_array(0, $user_roles)) {

                return true;
            }
            //manager
            if (in_array(1, $user_roles)) {
                $admins = explode(',', $CFG->siteadmins);
                if (in_array($userId, $admins)) {

                    return true;
                }
            }
            $hasEnableRole = $DB->get_records_sql('SELECT u.id FROM {user} u 
                                                   INNER JOIN {role_assignments} ra ON u.id = ra.userid
                                                   WHERE ra.roleid IN (:userroles) 
                                                   AND userid = :userid', 
                                                   array('userroles' => implode(',', $user_roles),
                                                         'userid' => $userId));
            $hasEnableRole = !empty($hasEnableRole);
        }

        return $hasEnableRole;
    }

   /**
     * return true if the user is a manager, false else
     * @param $userid      | int of the user we want to test
     * @return bool
     */
    public function is_manager($userid)
    {
        global $CFG;

        $admins = explode(',', $CFG->siteadmins);
    
        return in_array($userid, $admins);
    }

}