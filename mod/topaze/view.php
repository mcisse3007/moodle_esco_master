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
require_once($CFG->dirroot.'/mod/topaze/locallib.php');
require_once($CFG->dirroot.'/mod/scormlite/report/reportlib.php');

// Params
$id = required_param('id', PARAM_INT); 

// Objects and vars
$cm = get_coursemodule_from_id('topaze', $id, 0, false, MUST_EXIST);
$course = $DB->get_record("course", array("id"=>$cm->course), '*', MUST_EXIST);
$activity = $DB->get_record("topaze", array("id"=>$cm->instance), '*', MUST_EXIST);
$sco = $DB->get_record("scormlite_scoes", array("id"=>$activity->scoid), '*', MUST_EXIST);

//
// Page setup
//

require_login($course->id, false, $cm);
$url = new moodle_url('/mod/topaze/view.php', array('id'=>$id));
$PAGE->set_url($url);

//
// Check permissions
//

// Check activity visibility 
if (!$cm->visible and !has_capability('moodle/course:viewhiddenactivities', get_context_instance(CONTEXT_COURSE, $cm->course))) {
	print_error('activityiscurrentlyhidden');
}

//
// Logs
//

add_to_log($cm->course, 'topaze', 'view', "view.php?id={$cm->id}", $activity->id);

//
// Print the page
//

// Start
scormlite_print_header($cm, $activity, $course);

// Tabs
$playurl = "$CFG->wwwroot/mod/topaze/view.php?id=$cm->id";
$reporturl = "$CFG->wwwroot/mod/topaze/report/P1.php?id=$cm->id";
scormlite_print_tabs($cm, $activity, $playurl, $reporturl, 'play');

// Title and description
scormlite_print_title($cm, $activity);
scormlite_print_description($cm, $activity);

// My status box
$html = '';
$res = scormlite_get_myprofile($cm);
$html .= $res[0];
$res = scormlite_get_mystatus($cm, $sco, true, false);
$html .= $res[0];
$trackdata = $res[1];
$res = scormlite_get_availability($cm, $sco, $trackdata);
$html .= $res[0];
$scormopen = $res[1];
$res = topaze_get_myactions($cm, $sco, $trackdata, $scormopen);
$html .= $res[0];

echo $OUTPUT->box_start('generalbox mdl-align statusbox '.$trackdata->status);
echo $html;
echo $OUTPUT->box_end();

//
// The end
//

echo $OUTPUT->footer();

?>