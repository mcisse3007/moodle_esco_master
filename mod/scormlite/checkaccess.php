<?php

/* * *************************************************************
 *  This script has been developed for Moodle - http://moodle.org/
 *
 *  You can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
  *
 * ************************************************************* */

// Includes
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/scormlite/locallib.php');

// Params
$scoid = required_param('scoid', PARAM_INT);            // SCO id
$userid = optional_param('userid',$USER->id,PARAM_INT);	// User id
$attempt = optional_param('attempt', 1, PARAM_INT);     // Attempt

// Objects and vars
$sco = $DB->get_record("scormlite_scoes", array("id"=>$scoid), '*', MUST_EXIST);
$activity = scormlite_get_containeractivity($scoid, $sco->containertype);
$cm = get_coursemodule_from_instance($sco->containertype, $activity->id, 0, false, MUST_EXIST);

//
// Page setup
//

$url = new moodle_url('/mod/scormlite/datamodel.php', array('scoid'=>$scoid, 'id'=>$cm->id, 'userid'=>$userid, 'attempt'=>$attempt));
$PAGE->set_url($url);

//
// Check permissions
//

if (confirm_sesskey() && (!empty($scoid))) {
	if (scormlite_check_player_permissions($cm, $sco, $userid, $attempt)) echo "true\n0";
	else echo "false\n101";
} else {
	echo "false\n101";
}

