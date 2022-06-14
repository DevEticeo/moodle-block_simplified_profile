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
 * Block simplified_profile API
 *
 * @package    block_simplified_profile
 * @copyright  2022 may Eticeo <contact@eticeo.fr>
 * @author     2022 may Guevara Gabrielle <gabrielle.guevara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');

global $DB, $PAGE, $USER, $CFG;

require_login();

$instanceId         = optional_param('instanceId', null, PARAM_INT);
$currentPassword    = optional_param('currentPassword', null, PARAM_TEXT);
$newPassword        = optional_param('newPassword', null, PARAM_TEXT);
$newPasswordConfirm = optional_param('newPasswordConfirm', null, PARAM_TEXT);
$userId = optional_param('userId', $USER->id, PARAM_INT);

if (!$instanceId) {
    $instance = context_system::instance();
} else {
    $instance = $DB->get_record('block_instances', array('id' => $instanceId));
}
$blockSimplifiedProfile = block_instance('simplified_profile', $instance);


// call a method from the instance
if ($blockSimplifiedProfile) {
    $data = $blockSimplifiedProfile->update_student($userId, $currentPassword, $newPassword, $newPasswordConfirm);
    echo json_encode($data);
}
