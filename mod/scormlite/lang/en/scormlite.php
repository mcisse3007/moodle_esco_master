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

/**
 * Strings for component 'scormlite', language 'en'
 *
 */

// Plugin strings

$string['scormlite'] = 'SCORM Lite';
$string['modulename'] = 'SCORM Lite module';
$string['modulename_help'] = 'A SCORM Lite module is a simplified SCORM 2004 package, containing a single SCO, without manifest.';
$string['modulenameplural'] = 'SCORM Lite modules';
$string['pluginadministration'] = 'SCORM Lite administration';
$string['pluginname'] = 'SCORM Lite';
$string['page-mod-scormlite-x'] = 'Any SCORM Lite module page';
$string['dnduploadscormlite'] = 'Add SCORM Lite module';

// Permissions

$string['scormlite:reviewmycontent'] = 'Review my content';
$string['scormlite:reviewothercontent'] = 'Review other content';
$string['scormlite:viewmyreport'] = 'View my reports';
$string['scormlite:viewotherreport'] = 'View other reports';
$string['scormlite:addinstance'] = 'Add a new SCORM Lite module';

// Edit page (incl. module settings)

// General settings
$string['general'] = 'General data';
$string['title'] = 'Title';
$string['code'] = 'Code';
$string['code_help'] = 'The code is used as a short name to identify the content in reports.';
$string['package'] = 'Package file';
$string['package_help'] = 'The package file is a zip file containing SCORM Lite content (a mono-SCO with an index.html file at the root of the package).';
// Availability
$string['timerestrict'] = 'Restrict answering to this time period';
$string['manualopen'] = 'Availability';
$string['manualopendesc'] = 'This setting allows to force the activity opening or closing, without taking into account the opening and closing dates.';
$string['manualopendates'] = 'Use the dates';
$string['manualopenopen'] = 'Open';
$string['manualopenclose'] = 'Close';
$string['manualopenauto'] = 'Automatic';
$string['manualopenterminate'] = 'Terminate';
$string['scormopen'] = 'From';
$string['scormclose'] = 'Until';
// Advanced settings
$string['othersettings'] = 'Additional settings';
$string['maxtime'] = 'Max time (minutes)';
$string['maxtimedesc'] = 'Maximum time to pass a test. The maximum time must be expressed in minutes (e.g. 60 for a maximum time of 1 hour). 0 means that time is illimited.';
$string['maxtime_help'] = $string['maxtimedesc'];
$string['passingscore'] = 'Passing score (%)';
$string['passingscoredesc'] = 'The passing score is the minimum score that a learner must get in order to pass a test. You must enter an integer between 1 and 100.';
$string['passingscore_help'] = $string['passingscoredesc'];
$string['display'] = 'Display in';
$string['displaydesc'] = 'This preference sets the default of whether to display the package or not for an activity';
$string['currentwindow'] = 'Current window';
$string['popup'] = 'New window';
$string['displaychrono'] = 'Display chronometer';
$string['displaychronodesc'] = 'Display a chronomoter inside the content. This setting works only if the imported content has been designed for.';
$string['displaychrono_help'] = $string['displaychronodesc'];
// Attempts
$string['maximumattempts'] = 'Number of attempts';
$string['maximumattempts_help'] = 'This setting enables the number of attempts to be restricted.';
$string['maximumattemptsdesc'] = 'This preference sets the default maximum attempts for SCORMLite activities';
$string['whatgrade'] = 'Scoring method';
$string['whatgrade_help'] = 'If multiple attempts are allowed, this setting specifies whether the highest, average (mean), first or last completed attempt is used to set the score.';
$string['whatgradedesc'] = 'This preference sets the default scoring mode.';
$string['nolimit'] = 'Unlimited attempts';
$string['attempt1'] = '1 attempt';
$string['attemptsx'] = '{$a} attempts';
$string['highestattempt'] = 'Highest score';
$string['firstattempt'] = 'First score';
$string['lastattempt'] = 'Last score';
// Colors
$string['scorelessthan'] = 'Score <';
$string['scoreupto'] = 'Score <=';
$string['colors'] = 'Reporting colors';
$string['colorsdesc'] = 'Colors that should be used when displaying scores in reports. Each value indicates the score under which the color will apply.';
$string['colors_help'] = $string['colorsdesc'];
// Errors
$string['notvalidpackage'] = 'This file is not a valid SCORM Lite package!';
$string['notvalidmaxtime'] = 'You must enter a positive value';
$string['notvalidpassingscore'] = 'You must enter a value between 1 and 100';
$string['notvalidtresholdscore'] = 'You must enter a value between 0 and 100';
// Files
$string['areacontent'] = 'Content files';
$string['areapackage'] = 'Package file';

// Edit settings
$string['displayrank'] = 'Display rank';
$string['displayrankdesc'] = 'Display student ranks in reports.';


// Playing page

// Tabs
$string['tabplay'] = 'Play';
$string['tabreport'] = 'Report';
// SCORM status
$string['notattempted'] = 'Not attempted';
$string['incomplete'] = 'Incomplete';
$string['completed'] = 'Completed';
$string['passed'] = 'Passed';
$string['failed'] = 'Failed';
$string['suspended'] = 'Suspended';
// Status labels
$string['score'] = 'Score';
$string['started'] = 'Attempt started on';
$string['first'] = 'First access on';
$string['last'] = 'Last access on';
// Availability
$string['notautoopen'] = 'This activity is not available';
$string['notopen'] = 'This activity is not available';
$string['notopenyet'] = 'This activity is not available until {$a}';
$string['expired'] = 'This activity closed on {$a} and is no longer available';
// Attempts
$string['noattemptsallowed'] = 'Number of attempts allowed';
$string['noattemptsmade'] = 'Number of completed attempts';
$string['attempt'] = 'attempt';
$string['attemptcap'] = 'Attempt';
$string['attemptscap'] = 'Attempts';
$string['newattempt'] = 'New Attempt';
// Actions
$string['review'] = 'Review';
$string['start'] = 'Start';
$string['restart'] = 'Restart';
$string['resume'] = 'Resume';
// Content
$string['activityloading'] = 'You will be automatically redirected to the activity in';
$string['activitypleasewait'] = 'Activity loading, please wait ...';
$string['popupmessage'] = "Your content should have been opened in a new window. If not, you should check your browser settings to enable popup windows, before starting again.
Please, don't navigate in this window before closing the content window.
";
$string['recovery'] = 'The previous session of this content has abnormally stopped and will be restored.';
$string['notallowed'] = 'You are not allowed to do this!';
$string['accessdenied'] = 'You are not allowed to access this content!';
$string['exitactivity'] = 'Exit activity';
$string['exitcontent'] = 'Exit content';

// Report

$string['learner'] = 'Learner';
$string['status'] = 'Status';
$string['time'] = 'Time';
$string['totaltime'] = 'Time';
$string['action'] = 'Action';
$string['groupaverage'] = 'Average score for all students of the group';
$string['noreportdata'] = 'There is no data to report.';
$string['nogroupingdata'] = 'There is no grouping in this course.';
$string['nousergroupingdata'] = 'There is no grouping assigned to this user: {$a}.';
$string['noactivitygrouping'] = 'There is no grouping assigned to this activity.';
$string['selectgrouping'] = 'Please, select a grouping to display this report.';
$string['averagescore_short'] = 'Avg.';
$string['averagescore'] = 'Average';
$string['rank'] = 'Rank';
$string['activityreport'] = 'Activity report';
$string['select'] = '-- Select --';
$string['learnerresults'] = 'Results of learner <em>{$a}<em>';
$string['groupresults'] = 'Results of group <em>{$a}</em>';
$string['groupresults_nostyle'] = 'Results of group {$a}';
$string['groupprogress'] = 'Progress of group <em>{$a}</em>';
$string['progress'] = 'Progress';
// Features (buttons)
$string['exporthtml'] = 'Export HTML';
$string['exportcsv'] = 'Export CSV';
$string['exportxls'] = 'Export Excel';
$string['deletealltracks'] = 'Delete all tracks';
// Legend
$string['legend'] = 'Legend';
$string['legendR'] = 'R = Remedial';
$string['legendPC'] = 'PC = Progress Check';
$string['legendFE'] = 'FE = Final Exam';
// Attempts
$string['highestattemptdesc'] = 'Only the best attempts are displayed in the following table.';
$string['firstattemptdesc'] = 'Only the first attempts are displayed in the following table.';
$string['lastattemptdesc'] = 'Only the last attempts are displayed in the following table.';
$string['deleteattemps'] = 'Delete all the attempts of selected users';
$string['deleteattempsconfirm'] = 'Do you really want to delete all the attemps of the selected users?';
$string['deleteattempsno'] = 'You must close this activity before deleting attempts.';
// Dates
$string['strftimedatetimeshort'] = '%d/%m/%y, %H:%M';
// CVS titles
$string['learnercsv'] = 'Learner';
$string['firstcsv'] = 'FirstAccess';
$string['startedcsv'] = 'StartedOn';
$string['lastcsv'] = 'LastAccess';
$string['statuscsv'] = 'Status';
$string['incompletecsv'] = 'incomplete';
$string['completedcsv'] = 'completed';
$string['timecsv'] = 'Time';
$string['attemptcsv'] = 'Attempt';
$string['attemptscsv'] = 'Attempts';

 

 

