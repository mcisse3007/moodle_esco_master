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


if (!defined('MOODLE_INTERNAL')) {
	die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/scormlite/sharedlib.php');


class mod_scormlite_mod_form extends moodleform_mod {

	function definition()
	{
		global $CFG, $COURSE;
		$config = get_config('scormlite');
		$mform = $this->_form;

		//-------------------------------------------------------------------------------
		// General

		$mform->addElement('header', 'general', get_string('general', 'scormlite'));

		// Name
		$mform->addElement('text', 'name', get_string('name'));
		$mform->setType('name', PARAM_TEXT);
		$mform->addRule('name', null, 'required', null, 'client');

		// Code (kind of short name for the reports)
		$mform->addElement('text', 'code', get_string('code', 'scormlite'));
		$mform->setType('code', PARAM_TEXT);
		$mform->addRule('code', null, 'required', null, 'client');
		$mform->addHelpButton('code', 'code', 'scormlite');

		// Summary
		$this->add_intro_editor();

		// New local package upload
		$maxbytes = get_max_upload_file_size($CFG->maxbytes, $COURSE->maxbytes);
		$mform->setMaxFileSize($maxbytes);
		$mform->addElement('filepicker', 'packagefile', get_string('package','scormlite'));
		$mform->addHelpButton('packagefile', 'package', 'scormlite');
		$mform->addRule('packagefile', null, 'required', null, 'client');


		//-------------------------------------------------------------------------------
		// Availability

		$mform->addElement('header', 'timerestricthdr', get_string('timerestrict', 'scormlite'));

		// Manual opening
		$mform->addElement('select', 'manualopen', get_string('manualopen', 'scormlite'), scormlite_get_manualopen_display_array());
		$mform->setDefault('manualopen', $config->manualopen);

		// Opening date
		$mform->addElement('date_time_selector', 'timeopen', get_string("scormopen", "scormlite"));
		$mform->disabledIf('timeopen', 'manualopen', 'neq', 0);

		// Closing date
		$mform->addElement('date_time_selector', 'timeclose', get_string("scormclose", "scormlite"));
		$mform->disabledIf('timeclose', 'manualopen', 'neq', 0);


		//-------------------------------------------------------------------------------
		// Advanced settings

		$mform->addElement('header', 'advanced', get_string('othersettings', 'form'));

		// Maximum time
		$mform->addElement('text', 'maxtime', get_string('maxtime','scormlite'), 'maxlength="5" size="5"');
		$mform->setDefault('maxtime', $config->maxtime);
		$mform->setType('maxtime', PARAM_INT);
		$mform->addHelpButton('maxtime', 'maxtime', 'scormlite');
		$mform->addRule('maxtime', null, 'numeric', null, 'client');
		$mform->addRule('maxtime', null, 'nopunctuation', null, 'client');

		// Passing score
		$mform->addElement('text', 'passingscore', get_string('passingscore','scormlite'), 'maxlength="2" size="2"');
		$mform->setDefault('passingscore', $config->passingscore);
		$mform->setType('passingscore', PARAM_INT);
		$mform->addHelpButton('passingscore', 'passingscore', 'scormlite');
		$mform->addRule('passingscore', null, 'numeric', null, 'client');
		$mform->addRule('passingscore', null, 'nopunctuation', null, 'client');

		// Framed / Popup Window
		$mform->addElement('select', 'popup', get_string('display', 'scormlite'), scormlite_get_popup_display_array());
		$mform->setDefault('popup', $config->popup);

		// Chrono
		$mform->addElement('selectyesno', 'displaychrono', get_string('displaychrono', 'scormlite'));
		$mform->setDefault('displaychrono', $config->displaychrono);
		$mform->addHelpButton('displaychrono', 'displaychrono', 'scormlite');

        // Max Attempts
        $mform->addElement('select', 'maxattempt', get_string('maximumattempts', 'scormlite'), scormlite_get_attempts_array());
        $mform->addHelpButton('maxattempt', 'maximumattempts', 'scormlite');
        $mform->setDefault('maxattempt', $config->maxattempt);

        // What Attempt
        $mform->addElement('select', 'whatgrade', get_string('whatgrade', 'scormlite'),  scormlite_get_what_grade_array());
        $mform->disabledIf('whatgrade', 'maxattempt', 'eq', 1);
        $mform->addHelpButton('whatgrade', 'whatgrade', 'scormlite');
        $mform->setDefault('whatgrade', $config->whatgrade);

		//-------------------------------------------------------------------------------
		// Colors
		
		scormlite_form_add_colors($mform, 'scormlite');

		//-------------------------------------------------------------------------------
		// Common settings

		$this->standard_coursemodule_elements();

		//-------------------------------------------------------------------------------
		// Buttons

		$this->add_action_buttons();

		//-------------------------------------------------------------------------------
		// Hidden

		// Activity props
		$mform->addElement('hidden', 'scoid', 0);
		// SCOs props
		$mform->addElement('hidden', 'containertype', 'scormlite');
		$mform->addElement('hidden', 'scormtype', 'local');
		$mform->addElement('hidden', 'reference', '');
		$mform->addElement('hidden', 'sha1hash', '');
		$mform->addElement('hidden', 'revision', 0);
	}

	//
	// Form pre-processing
	//
	
	function data_preprocessing(&$default_values) {	
	
		// Colors
		scormlite_form_process_colors($default_values);

		// Get SCO data and assign it to the form  
		if (isset($default_values['scoid']) && $default_values['scoid'] != null) {
			global $DB;
			$scodata = $DB->get_record('scormlite_scoes', array('id'=>$default_values['scoid']), '*', false, MUST_EXIST);
			foreach ($scodata as $name => $value) {
				if ($name == 'id') $name = 'scoid';
				$default_values[$name] = $value;
			}
			$scoid = $scodata->id;
		} else {
			$scoid = 0;
		}

		// Packaging
		$draftitemid = file_get_submitted_draft_itemid('packagefile');
		file_prepare_draft_area($draftitemid, $this->context->id, 'mod_scormlite', 'package', $scoid);
		$default_values['packagefile'] = $draftitemid;

		// Time
		if (empty($default_values['timeopen'])) {
			$default_values['timeopen'] = 0;
		}
		if (empty($default_values['timeclose'])) {
			$default_values['timeclose'] = 0;
		}

		parent::data_preprocessing($default_values);
	}

	//
	// Form validation
	//
	
	function validation($data, $files) 	{
		$errors = array();
		
		// SCORM 2004 Lite Package
		
		if (empty($data['packagefile'])) {
			// If no file
			$errors['packagefile'] = get_string('required');
		} else {
			$files = $this->get_draft_files('packagefile');
			if (!$files || count($files)<1) {
				// If no file
				$errors['packagefile'] = get_string('required');
				return $errors;
			}
			// Upload and try to unzip
			$file = reset($files);
			global $CFG;
			$filename = "{$CFG->tempdir}/scormliteimport/scormlite_".time();
			make_temp_directory('scormliteimport');
			$file->copy_content_to($filename);
			$packer = get_file_packer('application/zip');
			$filelist = $packer->list_files($filename);			
			if (!is_array($filelist)) {
				// If not a package
				$errors['packagefile'] = get_string('notvalidpackage', 'scormlite');
			} else {
				// Check if the index.html file is at the package root
                $indexfound = false;
				foreach ($filelist as $info) {
					if ($info->pathname == 'index.html') {
						$indexfound = true;
						break;
					}
				}
				if (!$indexfound) {
					$errors['packagefile'] = get_string('notvalidpackage', 'scormlite');
				}
			}
			unlink($filename);
		}
		
		// Maximum time
		if ($data['maxtime'] < 0) {
			$errors['maxtime'] = get_string('notvalidmaxtime', 'scormlite');
		}
		
		// Passing score
		if ($data['passingscore'] < 1 || $data['passingscore'] > 100) {
			$errors['passingscore'] = get_string('notvalidpassingscore', 'scormlite');
		}

		// Colors
		scormlite_form_check_colors($data, $errors);
					
		return array_merge($errors, parent::validation($data, $files));
	}

}

