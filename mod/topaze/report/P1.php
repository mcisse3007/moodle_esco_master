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
require_once('../../../config.php');
require_once($CFG->dirroot.'/mod/scormlite/report/reportlib.php');
require_once($CFG->dirroot.'/mod/topaze/report/reportlib.php');

// Params
$id = required_param('id', PARAM_INT); 
$format  = optional_param('format', 'lms', PARAM_ALPHA);  // 'lms', 'csv', 'html', 'xls'

// Useful objects and vars
$cm = get_coursemodule_from_id('topaze', $id, 0, false, MUST_EXIST);
$course = $DB->get_record("course", array("id"=>$cm->course), '*', MUST_EXIST);
$activity = $DB->get_record("topaze", array("id"=>$cm->instance), '*', MUST_EXIST);
$sco = $DB->get_record("scormlite_scoes", array("id"=>$activity->scoid), '*', MUST_EXIST);

//
// Page setup 
//

$context = get_context_instance(CONTEXT_COURSE, $course->id);
require_login($course->id, false, $cm);
require_capability('mod/scormlite:viewotherreport', $context);
$url = new moodle_url('/mod/topaze/report/P1.php', array('id'=>$id));
if ($format == 'lms') $PAGE->set_url($url);

//
// Print the page
//

if ($format == 'lms') $title = topaze_report_print_activity_header($cm, $activity, $course);

//
// Fetch data
//

// Data
$users = array();
$userids = scormlite_report_populate_users_by_tracks($users, $course->id, $sco->id);
if (empty($users)) {
    echo '<p>'.get_string('noreportdata', 'scormlite').'</p>';
} else {
    $manifest = topaze_report_get_manifest($sco->id);
    topaze_report_populate_activity_results($users, $userids, $sco->id, $manifest);
    $withindic = isset($manifest->mainindicator);

    //
    // Print table
    //
    
    // Cols
    if ($format == 'lms') $cols = array('picture', 'fullname', 'attemptnb', 'start', 'last');
    else $cols = array('fullname', 'attemptnb', 'start', 'last');
    if ($withindic) $cols[] = 'indicator';
		
    // Headers
    if ($format == 'lms') {
        $headers = array('', get_string('learner', 'scormlite'), get_string('attemptscap', 'scormlite'), get_string('first', 'scormlite'), get_string('last', 'scormlite'));
        if ($withindic) $headers[] = get_string('result', 'topaze').'*';
    } else {
        $headers = array(get_string('learnercsv', 'scormlite'), get_string('attemptscsv', 'scormlite'), get_string('firstcsv', 'scormlite'), get_string('lastcsv', 'scormlite'));
        if ($withindic) $headers[] = get_string('resultcsv', 'topaze');
    }

    // Define table object
    $table = new flexible_table('mod-scormlite-report');
    $table->define_columns($cols);
    $table->define_headers($headers);
    $table->define_baseurl($url);

    // Presentation
    $table->sheettitle = ''; // workaround to avoid moodle table crash when using exporter
    $exporter = new scormlite_table_lms_export_format();
    if ($format == 'csv') $exporter = new scormlite_table_csv_export_format();
    else $exporter = new scormlite_table_lms_export_format();
    $table->export_class_instance($exporter);

    // Styles
    if ($format == 'lms') $table->column_class('picture', 'picture');
    $table->column_class('fullname', 'fullname');
    $table->column_class('attemptnb', 'attemptnb');
    $table->column_class('start', 'start');
    $table->column_class('last', 'last');
    if ($withindic) $table->column_class('indicator', 'indicator');
    
    // Setup
    $table->setup();

    // Fill
    $table->start_output();
    foreach ($users as $userid => $user) {
	$row = array();
	if ($format == 'lms') $row[] = $OUTPUT->user_picture($user);	
	$row[] = $user->lastname." ".$user->firstname;
    if ($format == 'lms') {
        $urlP2 = new moodle_url('/mod/topaze/report/P2.php', array('id'=>$id, 'userid'=>$userid));
        $row[] = '<a href="'.$urlP2.'">'.$user->attemptnb.'</a>';
    } else {
        $row[] = $user->attemptnb;                
    }
    $row[] = userdate($user->start, get_string('strftimedatetimeshort', 'scormlite'));
    $row[] = userdate($user->last, get_string('strftimedatetimeshort', 'scormlite'));
	if ($withindic) {
	    if (isset($user->indicator->value)) $row[] = $user->indicator->value;
	    else $row[] = '';
	}
	$table->add_data($row);
    }
    // The end	
    $table->finish_output();

    // Legend
    if ($format == 'lms' && $withindic) {
        echo '<p>*'.$manifest->mainindicator->title.'</p>';
    }
    
    // Export buttons
    if ($format == 'lms') {
        scormlite_print_exportbuttons(array('csv'=>$url));
    }

}

//
// The end
//

echo $OUTPUT->footer();

?>