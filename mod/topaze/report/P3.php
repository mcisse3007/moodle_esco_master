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
$userid = required_param('userid', PARAM_INT); 
$attempt = required_param('attempt', PARAM_INT); 
$format  = optional_param('format', 'lms', PARAM_ALPHA);  // 'lms', 'csv', 'html', 'xls'
$filter  = optional_param('filter', 1, PARAM_INT);  

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
$url = new moodle_url('/mod/topaze/report/P3.php', array('id'=>$id, 'userid'=>$userid, 'attempt'=>$attempt, 'filter'=>$filter));
if ($format == 'lms') $PAGE->set_url($url);

//
// Print the page
//

if ($format == 'lms') $titlelink = topaze_report_get_link_P1($cm->id);
else $titlelink = null;
if ($format == 'lms') $subtitlelink = topaze_report_get_link_P2($cm->id, $userid);
else $subtitlelink = null;
if ($format == 'lms') $title = topaze_report_print_activity_header($cm, $activity, $course, $titlelink, $userid, $subtitlelink, $attempt);

//
// Fetch data
//

$manifest = topaze_report_get_manifest($sco->id);
$tracks = topaze_report_get_user_attempt($sco->id, $userid, $attempt);
$topaze = topaze_report_get_topaze_json($tracks->suspend_data);


// ------ Main tracking data summary ------

/*
$indicator = topaze_report_get_main_indicator($topaze, $manifest);
echo '<h3>'.get_string('summary', 'topaze').'</h3>';
echo '<p class="topaze-P3-track">';
echo '<em>'.get_string('started', 'scormlite').':</em> '.userdate($tracks->start, get_string('strftimedatetimeshort', 'langconfig')).'<br>';
echo '<em>'.get_string('last', 'scormlite').':</em> '.userdate($tracks->finish, get_string('strftimedatetimeshort', 'langconfig')).'<br>';
echo '<em>'.get_string('time', 'scormlite').':</em> '.scormlite_format_duration($tracks->total_time).'<br>';
echo '<em>'.get_string('mainindicator', 'topaze').' ('.$indicator->title.'):</em> '.$indicator->value;
echo '</p>';
*/

// ------- Fetch data -------

$indicators = topaze_report_get_user_indicators($topaze, $manifest);
if ($activity->pathtracking == 1) {
    $steps = topaze_report_get_user_steps($topaze, $manifest, false);
} else {
    $steps = topaze_report_get_manifest_steps($topaze, $manifest);
}

if (empty($indicators) && empty($steps)) {
    echo '<p>'.get_string('noreportdata', 'scormlite').'</p>';
}


// ------ Indicators ------

if (!empty($indicators)) {

    echo '<h3>'.get_string('indicators', 'topaze').'</h3>';

    //
    // Print table
    //
    
    // Cols
    $cols = array('title', 'type', 'value');
		
    // Headers
    $headers = array(get_string('title', 'scormlite'), get_string('type', 'topaze'), get_string('value', 'topaze'));

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
    $table->column_class('title', 'title');
    $table->column_class('type', 'type');
    $table->column_class('value', 'value');

	// Setup
	$table->setup();

	// Fill
	$table->start_output();
	foreach ($indicators as $indicator) {
		$row = array();
		$row[] = $indicator->title;
		$row[] = get_string($indicator->type, 'topaze');
		$row[] = $indicator->value;
		$table->add_data($row);
	}
	// The end	
	$table->finish_output();

}


// ------ Learning path ------

    // Data
if (!empty($steps)) {
    
    if ($activity->pathtracking == 1) {
        echo '<h3>'.get_string('path', 'topaze').'</h3>';
    } else {
        echo '<h3>'.get_string('steps', 'topaze').'</h3>';        
    }
    
    //
    // Option links
    //
    
    echo '<p>';
    if ($filter) {
        $opturl = new moodle_url('/mod/topaze/report/P3.php', array('id'=>$id, 'userid'=>$userid, 'attempt'=>$attempt, 'filter'=>0));
        echo '<a href="'.$opturl.'">'.get_string('displayall', 'topaze').'</a>';
    } else {
        echo get_string('displayall', 'topaze');
    }
    echo '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
    if ($filter) {
        echo get_string('displayimportant', 'topaze');
    } else {
        $opturl = new moodle_url('/mod/topaze/report/P3.php', array('id'=>$id, 'userid'=>$userid, 'attempt'=>$attempt, 'filter'=>1));
        echo '<a href="'.$opturl.'">'.get_string('displayimportant', 'topaze').'</a>';
    }
    echo '</p>';

    //
    // Print table
    //
    
    if (topaze_report_has_important_steps($steps) || !$filter) {
    
        // Cols
        $cols = array('title', 'type', 'time', 'tracking');
            
        // Headers
        $headers = array(get_string('title', 'scormlite'), get_string('type', 'topaze'), get_string('time', 'scormlite'), get_string('tracking', 'topaze'));
    
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
        $table->column_class('title', 'title');
        $table->column_class('type', 'type');
        $table->column_class('time', 'time');
        $table->column_class('tracking', 'tracking');
    
        // Setup
        $table->setup();
    
        // Fill
        $table->start_output();
        foreach ($steps as $step) {
            if (is_array($step)) {
                // 3 periods
                $time = '';
                foreach ($step as $type => $part) {
                    if (isset($part->time)) {
                        $time .= get_string($type, 'topaze').': '.scormlite_format_duration_from_ms_for_csv($part->time).'<br>';
                    }
                }
                $step = $part;
            } else {
                // Single time
                if (isset($step->time)) {
                    $time = scormlite_format_duration_from_ms_for_csv($step->time);
                } else {
                    $time = '';
                }
            }
            if ($filter == 0 || $step->tracking) {
                $row = array();
                $row[] = $step->title;
                $row[] = get_string($step->type, 'topaze');
                $row[] = $time;
                if ($step->tracking) $row[] = get_string('important', 'topaze');
                else $row[] = '';
                $table->add_data($row);
            }
        }
        // The end	
        $table->finish_output();
        
    }
}

//
// The end
//

echo $OUTPUT->footer();

?>