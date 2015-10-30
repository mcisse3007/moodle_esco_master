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
 * This file contains the definition for the library class for onlinepoodll submission plugin
 *
 * This class provides all the functionality for the new assign module.
 *
 * @package    assignsubmission_onlinepoodll
 * @copyright 2012 Justin Hunt {@link http://www.poodll.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
//Get our poodll resource handling lib
require_once($CFG->dirroot . '/filter/poodll/poodllresourcelib.php');

/** Include eventslib.php */
require_once($CFG->libdir.'/eventslib.php');

defined('MOODLE_INTERNAL') || die();
/**
 * File area/component/table name for online text submission assignment
 */
define('ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA', 'submissions_onlinepoodll');
define('ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT', 'assignsubmission_onlinepoodll');
define('ASSIGNSUBMISSION_ONLINEPOODLL_CONFIG_COMPONENT', 'assignsubmission_onlinepoodll');
define('ASSIGNSUBMISSION_ONLINEPOODLL_TABLE', 'assignsubmission_onlinepoodl');
define('ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA', 'onlinepoodll_backimage');


//some constants for the type of online poodll assignment
define('OP_REPLYMP3VOICE',0);
define('OP_REPLYVOICE',1);
define('OP_REPLYVIDEO',2);
define('OP_REPLYWHITEBOARD',3);
define('OP_REPLYSNAPSHOT',4);
define('OP_REPLYTALKBACK',5);


define('OP_FILENAMECONTROL','onlinepoodll');
define('OP_VECTORCONTROL','onlinepoodll_vector');



/**
 * library class for onlinepoodll submission plugin extending submission plugin base class
 *
 * @package    assignsubmission_onlinepoodll
 * @copyright 2012 Justin Hunt {@link http://www.poodll.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_submission_onlinepoodll extends assign_submission_plugin {

    /**
     * Get the name of the online text submission plugin
     * @return string
     */
    public function get_name() {
        return get_string('onlinepoodll', ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT);
    }

	    /**
     * Get the settings for Onbline PoodLLsubmission plugin form
     *
     * @global stdClass $CFG
     * @global stdClass $COURSE
     * @param MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_settings(MoodleQuickForm $mform) {
        global $CFG, $COURSE;

        $recordertype = $this->get_config('recordertype');
		$boardsize = $this->get_config('boardsize');
		$backimage = $this->get_config('backimage');
		$timelimit = $this->get_config('timelimit');
      
      
       //get allowed recorders from admin settings
		$allowed_recorders = get_config('assignsubmission_onlinepoodll', 'allowedrecorders');
		$allowed_recorders  = explode(',',$allowed_recorders);
		$recorderoptions = array();
		if(array_search(OP_REPLYMP3VOICE,$allowed_recorders)!==false){
			$recorderoptions[OP_REPLYMP3VOICE] = get_string("replymp3voice", "assignsubmission_onlinepoodll");
		}
		if(array_search(OP_REPLYVOICE,$allowed_recorders)!==false){
			$recorderoptions[OP_REPLYVOICE] = get_string("replyvoice", "assignsubmission_onlinepoodll");
		}
		if(array_search(OP_REPLYVIDEO ,$allowed_recorders)!==false){
			$recorderoptions[OP_REPLYVIDEO ] = get_string("replyvideo", "assignsubmission_onlinepoodll");
		}
		if(array_search(OP_REPLYWHITEBOARD,$allowed_recorders)!==false){
			$recorderoptions[OP_REPLYWHITEBOARD ] = get_string("replywhiteboard", "assignsubmission_onlinepoodll");
		}
		if(array_search(OP_REPLYSNAPSHOT,$allowed_recorders)!==false){
			$recorderoptions[OP_REPLYSNAPSHOT] = get_string("replysnapshot", "assignsubmission_onlinepoodll");
		}
      
      
	/*
        $recorderoptions = array( OP_REPLYMP3VOICE => get_string("replymp3voice", "assignsubmission_onlinepoodll"), 
				OP_REPLYVOICE => get_string("replyvoice", "assignsubmission_onlinepoodll"), 
				OP_REPLYVIDEO => get_string("replyvideo", "assignsubmission_onlinepoodll"),
				OP_REPLYWHITEBOARD => get_string("replywhiteboard", "assignsubmission_onlinepoodll"),
				OP_REPLYSNAPSHOT => get_string("replysnapshot", "assignsubmission_onlinepoodll"));
	*/
		
		//we don't support talkback yet
		//OP_REPLYTALKBACK => get_string("replytalkback", "assignsubmission_onlinepoodll"));
        
		$mform->addElement('select', 'assignsubmission_onlinepoodll_recordertype', get_string("recordertype", "assignsubmission_onlinepoodll"), $recorderoptions);
        //$mform->addHelpButton('assignsubmission_onlinepoodll_recordertype', get_string('onlinepoodll', ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT), ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT);
        $mform->setDefault('assignsubmission_onlinepoodll_recordertype', $recordertype);
		$mform->disabledIf('assignsubmission_onlinepoodll_recordertype', 'assignsubmission_onlinepoodll_enabled', 'eq', 0);

		//Add a place to set a maximum recording time.
	   $mform->addElement('duration', 'assignsubmission_onlinepoodll_timelimit', get_string('timelimit', 'assignsubmission_onlinepoodll'));    
       $mform->setDefault('assignsubmission_onlinepoodll_timelimit', $timelimit);
		$mform->disabledIf('assignsubmission_onlinepoodll_timelimit', 'assignsubmission_onlinepoodll_enabled', 'eq', 0);
		$mform->disabledIf('assignsubmission_onlinepoodll_timelimit', 'assignsubmission_onlinepoodll_recordertype', 'eq', OP_REPLYWHITEBOARD);
		$mform->disabledIf('assignsubmission_onlinepoodll_timelimit', 'assignsubmission_onlinepoodll_recordertype', 'eq', OP_REPLYSNAPSHOT);
	  
	  //these are for the whiteboard submission
	  // added Justin 20121216 back image, and boardsizes, part of whiteboard response
		//For the back image, we 
		//(i) first have to load existing back image files into a draft area
		// (ii) add a file manager element
		//(iii) set the draft area info as the "default" value for the file manager
		if(array_search(OP_REPLYWHITEBOARD,$allowed_recorders)!==false){
			$itemid = 0;
			$draftitemid = file_get_submitted_draft_itemid(ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA);
			$context =  $this->assignment->get_context();
			if($context) {
				$contextid = $context->id;
			}else{
				$contextid = 0;
			}
			file_prepare_draft_area($draftitemid, $contextid, ASSIGNSUBMISSION_ONLINEPOODLL_CONFIG_COMPONENT, ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA, 
			$itemid,
			array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
			$mform->addElement('filemanager', 'backimage', get_string('backimage', 'assignsubmission_onlinepoodll'), null,array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
			$mform->setDefault('backimage', $backimage);
			//commented 20130120 bcause was broken with moodle 2.6. Errors saying "must attach no more than one file" when tried to save, empty, disabled
			//$mform->disabledIf('backimage', 'assignsubmission_onlinepoodll_enabled', 'eq', 0);
			//$mform->disabledIf('backimage', 'assignsubmission_onlinepoodll_recordertype', 'ne', OP_REPLYWHITEBOARD );
		}else{
			$mform->addElement('hidden', 'backimage',$backimage);
		}

		
		//board sizes
		if(array_search(OP_REPLYWHITEBOARD,$allowed_recorders)!==false){
			$boardsizes = array(
				'320x320' => '320x320',
				'400x600' => '400x600',
				'500x500' => '500x500',
				'600x400' => '600x400',
				'600x800' => '600x800',
				'800x600' => '800x600'
				);
			$mform->addElement('select', 'assignsubmission_onlinepoodll_boardsize',
				get_string('boardsize', 'assignsubmission_onlinepoodll'), $boardsizes);
			$mform->setDefault('assignsubmission_onlinepoodll_boardsize', $boardsize);
			$mform->disabledIf('assignsubmission_onlinepoodll_boardsize', 'assignsubmission_onlinepoodll_enabled', 'eq', 0);
			$mform->disabledIf('assignsubmission_onlinepoodll_boardsize', 'assignsubmission_onlinepoodll_recordertype', 'ne', OP_REPLYWHITEBOARD );
		}else{
			$mform->addElement('hidden', 'assignsubmission_onlinepoodll_boardsize',$boardsize);
		}

    }
    
    /**
     * Save the settings for file submission plugin
     *
     * @param stdClass $data
     * @return bool 
     */
    public function save_settings(stdClass $data) {
        $this->set_config('recordertype', $data->assignsubmission_onlinepoodll_recordertype);
		//if we have a board size, set it
		if(isset($data->assignsubmission_onlinepoodll_boardsize)){
			$this->set_config('boardsize', $data->assignsubmission_onlinepoodll_boardsize);
		}else{
			$this->set_config('boardsize', '320x320');
		}
		
		//if we have a time limit, set it
		if(isset($data->assignsubmission_onlinepoodll_timelimit)){
			$this->set_config('timelimit', $data->assignsubmission_onlinepoodll_timelimit);
		}else{
			$this->set_config('timelimit', 0);
		}
		
		
		// $this->set_config('backimage', $data->assignsubmission_onlinepoodll_backimage);
		//error_log(print_r($this->assignment,true));
		//error_log(print_r($data,true));
		$itemid = $data->instance;
		//error_log(print_r($this,true));
		//$itemid = $this->id;
		$itemid = 0;
		if(isset($data->backimage)){
			file_save_draft_area_files($data->backimage, 
								$this->assignment->get_context()->id, 
								ASSIGNSUBMISSION_ONLINEPOODLL_CONFIG_COMPONENT,
								ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA, $itemid, 
								array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
			$this->set_config('backimage', $data->backimage);
		}else{
			$this->set_config('backimage',null);
		}
	
	/*
		error_log(print_r($data,true));
	$draftitemid = file_get_submitted_draft_itemid('assignsubmission_onlinepoodll_backimage');
	file_prepare_draft_area($draftitemid, $this->context->id, ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA, 
		!empty($data->coursemodule) ? (int) $data->coursemodule : null,
		array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
		*/
	

        return true;
    }

   /**
    * Get onlinepoodll submission information from the database
    *
    * @param  int $submissionid
    * @return mixed
    */
    private function get_onlinepoodll_submission($submissionid) {
        global $DB;

        return $DB->get_record(ASSIGNSUBMISSION_ONLINEPOODLL_TABLE, array('submission'=>$submissionid));
    }

    /**
     * Add form elements onlinepoodll submissions
     *
     * @param mixed $submission can be null
     * @param MoodleQuickForm $mform
     * @param stdClass $data
     * @return true if elements were added to the form
     */
    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
		 global $CFG, $USER;
		 
        $elements = array();

        $submissionid = $submission ? $submission->id : 0;


        if ($submission && get_config('assignsubmission_onlinepoodll', 'showcurrentsubmission')) {
            $onlinepoodllsubmission = $this->get_onlinepoodll_submission($submission->id);
            $size=get_config('assignsubmission_onlinepoodll', 'displaysize_single');
			//show the previous submission in a player or whatever
			$mform->addElement('static', 'currentsubmission', 
				get_string('currentsubmission', 'assignsubmission_onlinepoodll') ,
				$this->fetchResponses($submission->id,false,false));
			
        }
        
         if (!isset($data->vectordata)) {
            $data->vectordata = '';
        }
        if ($submission) {
            $onlinepoodllsubmission = $this->get_onlinepoodll_submission($submission->id);
            if ($onlinepoodllsubmission) {
                $data->vectordata = $onlinepoodllsubmission->vectordata;
            }

        }

		//We prepare our form here and fetch/save data in SAVE method
		$usercontextid=context_user::instance($USER->id)->id;
		$draftitemid = file_get_submitted_draft_itemid(OP_FILENAMECONTROL);
		$contextid=$this->assignment->get_context()->id;
		file_prepare_draft_area($draftitemid, $contextid, ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA, $submissionid, null,null);
		$mform->addElement('hidden', 'draftitemid', $draftitemid);
		$mform->addElement('hidden', 'usercontextid', $usercontextid);	
		$mform->addElement('hidden', OP_FILENAMECONTROL, '',array('id' => OP_FILENAMECONTROL));
		$mform->addElement('hidden', OP_VECTORCONTROL, $data->vectordata,array('id' => OP_VECTORCONTROL));
		$mform->setType('draftitemid', PARAM_INT);
		$mform->setType('usercontextid', PARAM_INT); 
		$mform->setType(OP_FILENAMECONTROL, PARAM_TEXT); 
		$mform->setType(OP_VECTORCONTROL, PARAM_TEXT); 
	
		//get timelimit for recorders if set
		$timelimit = $this->get_config('timelimit');
		
		//fetch the required "recorder
		switch($this->get_config('recordertype')){
			
			case OP_REPLYVOICE:
				$mediadata= fetchAudioRecorderForSubmission('swf','onlinepoodll',OP_FILENAMECONTROL, $usercontextid ,'user','draft',$draftitemid,$timelimit);
				$mform->addElement('static', 'description', '',$mediadata);

				break;
				
			case OP_REPLYMP3VOICE:
				$mediadata= fetchMP3RecorderForSubmission(OP_FILENAMECONTROL, $usercontextid ,'user','draft',$draftitemid,$timelimit);
				$mform->addElement('static', 'description', '',$mediadata);
				break;
				
			case OP_REPLYWHITEBOARD:
				//get board sizes
				switch($this->get_config('boardsize')){
					case "320x320": $width=320;$height=320;break;
					case "400x600": $width=400;$height=600;break;
					case "500x500": $width=500;$height=500;break;
					case "600x400": $width=600;$height=400;break;
					case "600x800": $width=600;$height=800;break;
					case "800x600": $width=800;$height=600;break;
				}


				//Get Backimage, if we have one
				// get file system handle for fetching url to submitted media prompt (if there is one) 
				$fs = get_file_storage();
				$itemid=0;
				$files = $fs->get_area_files($contextid, ASSIGNSUBMISSION_ONLINEPOODLL_CONFIG_COMPONENT, 
								ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA, 
								$itemid);
				$imageurl="";
				if($files && count($files)>0){
					$file = array_pop($files);
					$imageurl = file_rewrite_pluginfile_urls('@@PLUGINFILE@@/' . $file->get_filename(), 
								'pluginfile.php', 
								$file->get_contextid(), 
								$file->get_component(), 
								$file->get_filearea(), 
								$file->get_itemid());
				
				}
				
				$vectorcontrol = OP_VECTORCONTROL;
				$vectordata=$data->vectordata;
				$mediadata= fetchWhiteboardForSubmission(OP_FILENAMECONTROL, $usercontextid ,'user','draft',$draftitemid, $width, $height, $imageurl,'','',$vectorcontrol,$vectordata);
				$mform->addElement('static', 'description', '',$mediadata);
				break;
			
			case OP_REPLYSNAPSHOT:
				$mediadata= fetchSnapshotCameraForSubmission(OP_FILENAMECONTROL,"snap.jpg" ,350,400,$usercontextid ,'user','draft',$draftitemid);
				$mform->addElement('static', 'description', '',$mediadata);
				break;

			case OP_REPLYVIDEO:
				$mediadata= fetchVideoRecorderForSubmission('swf','onlinepoodll',OP_FILENAMECONTROL, $usercontextid ,'user','draft',$draftitemid,$timelimit);
				$mform->addElement('static', 'description', '',$mediadata);			
									
				break;
					
		}

        // hidden params
        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);
		return true;

    }

	
	
	/*
	* Fetch the player to show the submitted recording(s)
	*
	*
	*
	*/
	function fetchResponses($submissionid, $checkfordata=false, $embed=false, $size=320){
		global $CFG;
		
		$responsestring = "";
		
		 //This is old code for poodll players(obselete) 
		 //needs to be checked but probably safe to delete
        $embedstring = 'clicktoplay';
        $embed='false';
		
		
		//get filename, from the filearea for this submission. 
		//there should be only one.
		$fs = get_file_storage();
		$filename="";
        $files = $fs->get_area_files($this->assignment->get_context()->id, 
			ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, 
			ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA, 
			$submissionid, "id", false);
			
        //if we have files, lets get them ready
		if (!empty($files)) {
			//if the filename property exists, and is filled, use that to fetch the file
			$onlinepoodllsubmission = $this->get_onlinepoodll_submission($submissionid);
			if(isset($onlinepoodllsubmission->filename) && !empty($onlinepoodllsubmission->filename)){
				$filename =  $onlinepoodllsubmission->filename;
				
			//if no filename property just take the first file. That is how we used to do it	
			}else{
				foreach ($files as $file) {
					$filename = $file->get_filename();
					break;
				}
			}
		}
	
		
		//if this is a playback area, for teacher, show a string if no file
		if ($checkfordata  && empty($filename)){ 
					$responsestring .= "No submission found";
					
		//if the student is viewing and there is no file , just show an empty area
		}elseif(empty($filename)){
				$responsestring .= "";
				
		}else{	
			//The path to any media file we should play
			$rawmediapath = $CFG->wwwroot.'/pluginfile.php/'.$this->assignment->get_context()->id.'/'. ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT .'/' . ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA  . '/'.$submissionid.'/'.$filename;
			$mediapath = urlencode($rawmediapath);
		
			//prepare our response string, which will parsed and replaced with the necessary player
			switch($this->get_config('recordertype')){
							
				case OP_REPLYVOICE:
				case OP_REPLYTALKBACK:
					//if we are using red5 recorder, flv files need to be shown in audio player, so we force poodll type audio
					if(stripos($filename,'.mp3')){
						$responsestring .= format_text("<a href=\"$rawmediapath\">$filename</a>", FORMAT_HTML);
					}else{
						$responsestring .= format_text('{POODLL:type=audio,path='.	$mediapath .',protocol=http,embed=' . $embed . ',embedstring='. $embedstring .'}', FORMAT_HTML);
					}
					break;
					
				case OP_REPLYMP3VOICE:
						//originally tried to force poodll, but best to default to whatever
						//$responsestring .= format_text('{POODLL:type=audio,path='.	$mediapath .',protocol=http,embed=' . $embed . ',embedstring='. $embedstring .'}', FORMAT_HTML);
						$responsestring .= format_text("<a href=\"$rawmediapath\">$filename</a>", FORMAT_HTML);
						break;						
					
				case OP_REPLYVIDEO:
						if($size==0){
							$responsestring=get_string('videoplaceholder','assignsubmission_onlinepoodll');
							break;
						}
						
						//get our size profile
						switch($size){
							case "0": $width=320;$height=240;break;
							case "160": $width=160;$height=120;break;
							case "320": $width=320;$height=240;break;
							case "480": $width=480;$height=360;break;
							case "640": $width=640;$height=480;break;
							case "800": $width=800;$height=600;break;
							case "1024": $width=1024;$height=768;break;
							default:$width=320;$height=240;
						}
						$responsestring .= format_text("<a href=\"$rawmediapath?d=" . $width . "x" . $height . "\">$filename</a>", FORMAT_HTML);
						//$responsestring .= format_text('{POODLL:type=video,path='.	$mediapath .',protocol=http,embed=' . $embed . ',embedstring='. $embedstring .'}', FORMAT_HTML);
						break;

				case OP_REPLYWHITEBOARD:
					$responsestring .= "<img alt=\"submittedimage\" width=\"" . $CFG->filter_poodll_videowidth . "\" src=\"" . urldecode($mediapath) . "\" />";
					break;
					
				case OP_REPLYSNAPSHOT:
					$responsestring .= "<img alt=\"submittedimage\" width=\"" . $CFG->filter_poodll_videowidth . "\" src=\"" . urldecode($mediapath) . "\" />";
					break;
					
				default:
					$responsestring .= format_text("<a href=\"$rawmediapath\">$filename</a>", FORMAT_HTML);
					break;	
				
			}//end of switch
		}//end of if (checkfordata ...) 
		
		
		
		return $responsestring;
		
	}//end of fetchResponses
	
	
	
     /**
      * Save data to the database
      *
      * @param stdClass $submission
      * @param stdClass $data
      * @return bool
      */
     public function save(stdClass $submission, stdClass $data) {
        global $DB;


		//Move recorded files from draft to the correct area
		//if filename is false, no update. possibly used changed something else on page
		$filename = $this->shift_draft_file($submission);
		if(!$filename){return true;}
		
        $onlinepoodllsubmission = $this->get_onlinepoodll_submission($submission->id);
        //fetch any vectordata we might have for the whiteboard
        $vectordata = optional_param(OP_VECTORCONTROL, '', PARAM_RAW);
        
        if ($onlinepoodllsubmission) {
			$onlinepoodllsubmission->filename = $filename;
			$onlinepoodllsubmission->vectordata = $vectordata;
            return $DB->update_record(ASSIGNSUBMISSION_ONLINEPOODLL_TABLE, $onlinepoodllsubmission);
        } else {
            $onlinepoodllsubmission = new stdClass();
            $onlinepoodllsubmission->submission = $submission->id;
            $onlinepoodllsubmission->assignment = $this->assignment->get_instance()->id;
            $onlinepoodllsubmission->recorder = $this->get_config('recordertype');
			$onlinepoodllsubmission->filename = $filename;
			$onlinepoodllsubmission->vectordata = $vectordata;
            return $DB->insert_record(ASSIGNSUBMISSION_ONLINEPOODLL_TABLE, $onlinepoodllsubmission) > 0;
        }


    }
    
    
    function shift_draft_file($submission) {
        global $CFG, $USER, $DB,$COURSE;

		//When we add the recorder via the poodll filter, it adds a hidden form field of the name OP_FILENAMECONTROL
		//the recorder updates that field with the filename of the audio/video it recorded. We pick up that filename here.

		$filename = optional_param(OP_FILENAMECONTROL, '', PARAM_RAW);
		$draftitemid = optional_param('draftitemid', '', PARAM_RAW);
		$usercontextid = optional_param('usercontextid', '', PARAM_RAW);
		
		//Don't do anything in the case that the filename is empty
		//possibly the user is just updating something else on the page(eg an online text submission)
		//if we overwrite here, we might trash their existing poodll submission file
		if($filename==''){return false;}
		
		 $fs = get_file_storage();
		 $browser = get_file_browser();
         $fs->delete_area_files($this->assignment->get_context()->id, ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT,ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA , $submission->id);

		
		//fetch the file info object for our original file
		$original_context = context::instance_by_id($usercontextid);
		$draft_fileinfo = $browser->get_file_info($original_context, 'user','draft', $draftitemid, '/', $filename);
	
		//perform the copy	
		if($draft_fileinfo){
			
			//create the file record for our new file
			$file_record = array(
			'userid' => $USER->id,
			'contextid'=>$this->assignment->get_context()->id, 
			'component'=>ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, 
			'filearea'=>ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA ,
			'itemid'=>$submission->id, 
			'filepath'=>'/', 
			'filename'=>$filename,
			'author'=>'moodle user',
			'license'=>'allrighttsreserved',		
			'timecreated'=>time(), 
			'timemodified'=>time()
			);
			$ret = $draft_fileinfo->copy_to_storage($file_record);
			
		}//end of if $draft_fileinfo
		
		return $filename;

	}//end of shift_draft_file
    



    /**
     * Display the list of files  in the submission status table
     *
     * @param stdClass $submission
     * @param bool $showviewlink Set this to true if the list of files is long
     * @return string
     */
    public function view_summary(stdClass $submission, & $showviewlink) {
    	$showviewlink = false;
    	
    	//fetch video size for video list display pages
        $size = get_config('assignsubmission_onlinepoodll', 'displaysize_list');

		//our response, this will output a player/image, and optionally a portfolio export link
		return $this->fetchResponses($submission->id,false,false,$size) . $this->get_p_links($submission->id) ;
		//rely on get_files from now on to generate portfolio links Justin 19/06/2014
		//return $this->fetchResponses($submission->id,false,false,$size) ;

		//the default return method, this just produces a link.
      // return $this->assignment->render_area_files(ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA, $submission->id);
    }

      /**
     * Produce a list of files suitable for export that represent this feedback or submission
     *
     * @param stdClass $submission The submission
     * @return array - return an array of files indexed by filename
     */
    public function get_files(stdClass $submission, stdClass $user=null) {
        $result = array();
        $fs = get_file_storage();

        $files = $fs->get_area_files($this->assignment->get_context()->id, ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA, $submission->id, "timemodified", false);

        foreach ($files as $file) {
		
			//let NOT return splash images for videos
			if($this->get_config('recordertype')== OP_REPLYVIDEO){
				$fname = $file->get_filename();
				$fext = pathinfo($fname, PATHINFO_EXTENSION);				
				if($fext == 'jpg' || $fext == 'png'){
					continue;
				}
			}
		
            $result[$file->get_filename()] = $file;
        }
        return $result;
    }

    /**
     * Display the saved text content from the editor in the view table
     *
     * @param stdClass $submission
     * @return string
     */
    public function view(stdClass $submission) {
        $result = '';

        $onlinepoodllsubmission = $this->get_onlinepoodll_submission($submission->id);
        //fetch video size for single video display pages
        $size = get_config('assignsubmission_onlinepoodll', 'displaysize_single');


        if ($onlinepoodllsubmission) {


            // show our responses in a player
			$result = $this->fetchResponses($submission->id,false,false,$size);
		
			//the default render method. Only shows a link
			// return $this->assignment->render_area_files(ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, ASSIGN_FILEAREA_SUBMISSION_ONLINEPOODLL, $submission->id);

        }

        return $result;
    }
	
	
	    /**
     * Produces a list of portfolio links to the file recorded byuser
     *
     * @param $submissionid this submission's id
     * @return string the portfolio export link
     */
    public function get_p_links($submissionid) {
        global $CFG, $OUTPUT, $DB;

		$output ="";
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->assignment->get_context()->id, 
					ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT, 
					ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA, 
					$submissionid, "id", false);
					
        if (!empty($files)) {
        	//this was nec for portfolios prior to M2.7. 
        	if(file_exists($CFG->dirroot . '/mod/assignment/locallib.php')){
            	require_once($CFG->dirroot . '/mod/assignment/locallib.php');
            }
            if ($CFG->enableportfolios) {
                require_once($CFG->libdir.'/portfoliolib.php');
            }
            
			//Add portfolio download links if appropriate
            foreach ($files as $file) {
				
				//in the case of splash images we will have two files.
				//we just want one link, and for the video file
				if($this->get_config('recordertype')== OP_REPLYVIDEO){
					$fname = $file->get_filename();
					$fext = pathinfo($fname, PATHINFO_EXTENSION);				
					if($fext == 'jpg' || $fext == 'png'){
						continue;
					}
				}
                
				
				if ($CFG->enableportfolios && has_capability('mod/assign:exportownsubmission', $this->assignment->get_context())){
					require_once($CFG->libdir . '/portfoliolib.php');
					$button = new portfolio_add_button();
                   
				   //API changes. See https://github.com/moodle/moodle/blob/master/portfolio/upgrade.txt#L6
				   if($CFG->version < 2012120300){
						$finalparam='/mod/assign/portfolio_callback.php';
					}else{
						$finalparam='mod_assign';
					}
				   
					$button->set_callback_options('assign_portfolio_caller', 
							array('cmid' => $this->assignment->get_course_module()->id,
											'component' => ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT,
											'area'=>ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA,
											'sid' => $submissionid),
							$finalparam);
                    $button->set_format_by_file($file);
                    $output .= $button->to_html(PORTFOLIO_ADD_TEXT_LINK);
                }
               
                $output .= '<br />' ;
            }
        }

        $output = '<div class="files" style="float:left;margin-left:5px;">'.$output.'</div><br clear="all" />';
        
        return $output;

    }

     /**
     * Return true if this plugin can upgrade an old Moodle 2.2 assignment of this type and version.
     *
     * @param string $type old assignment subtype
     * @param int $version old assignment version
     * @return bool True if upgrade is possible
     */
    public function can_upgrade($type, $version) {
        if ($type == 'poodllonline' && $version >= 2011112900) {
            return true;
        }
        return false;
    }


    /**
     * Upgrade the settings from the old assignment to the new plugin based one
     *
     * @param context $oldcontext - the database for the old assignment context
     * @param stdClass $oldassignment - the database for the old assignment instance
     * @param string $log record log events here
     * @return bool Was it a success?
     */
    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log) {
		switch($oldassignment->var3){
			case 0:
			case 1:
			case 2:
				$this->set_config('recordertype',  OP_REPLYVOICE);
				break;
			case 7:
				$this->set_config('recordertype',  OP_REPLYMP3VOICE);
				break;
			case 3:
			case 4:
				$this->set_config('recordertype',  OP_REPLYVIDEO);
				break;
			case 5:
				$this->set_config('recordertype',  OP_REPLYTALKBACK);
				break;
			case 6:
				$this->set_config('recordertype',  OP_REPLYWHITEBOARD);
				break;
		}

        return true;
    }

    /**
     * Upgrade the submission from the old assignment to the new one
     *
     * @param context $oldcontext - the database for the old assignment context
     * @param stdClass $oldassignment The data record for the old assignment
     * @param stdClass $oldsubmission The data record for the old submission
     * @param stdClass $submission The data record for the new submission
     * @param string $log Record upgrade messages in the log
     * @return bool true or false - false will trigger a rollback
     */
    public function upgrade(context $oldcontext, stdClass $oldassignment, stdClass $oldsubmission, stdClass $submission, & $log) {
        global $DB;

        $onlinepoodllsubmission = new stdClass();

        $onlinepoodllsubmission->submission = $submission->id;
        $onlinepoodllsubmission->assignment = $this->assignment->get_instance()->id;

    

        if (!$DB->insert_record(ASSIGNSUBMISSION_ONLINEPOODLL_TABLE, $onlinepoodllsubmission) > 0) {
            $log .= get_string('couldnotconvertsubmission', 'mod_assign', $submission->userid);
            return false;
        }

        // now copy the area files
        $this->assignment->copy_area_files_for_upgrade($oldcontext->id,
                                                        'mod_assignment',
                                                        'submission',
                                                        $oldsubmission->id,
                                                        // New file area
                                                        $this->assignment->get_context()->id,
                                                        ASSIGNSUBMISSION_ONLINEPOODLL_COMPONENT,
                                                        ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA,
                                                        $submission->id);
        return true;
    }

    /**
     * Formatting for log info
     *
     * @param stdClass $submission The new submission
     * @return string
     */
    public function format_for_log(stdClass $submission) {
        // format the info for each submission plugin add_to_log
      //  $onlinepoodllsubmission = $this->get_onlinepoodll_submission($submission->id);
        $onlinepoodllloginfo = '';

        $onlinepoodllloginfo .= "submission id:" . $submission->id . " added.";

        return $onlinepoodllloginfo;
    }

    /**
     * The assignment has been deleted - cleanup
     *
     * @return bool
     */
    public function delete_instance() {
        global $DB;
        // will throw exception on failure
        $DB->delete_records(ASSIGNSUBMISSION_ONLINEPOODLL_TABLE, array('assignment'=>$this->assignment->get_instance()->id));

        return true;
    }

    /**
     * No text is set for this plugin
     *
     * @param stdClass $submission
     * @return bool
     */
    public function is_empty(stdClass $submission) {
        return $this->view($submission) == '';
    }

    /**
     * Get file areas returns a list of areas this plugin stores files
     * @return array - An array of fileareas (keys) and descriptions (values)
     */
    public function get_file_areas() {
  
        return array(ASSIGNSUBMISSION_ONLINEPOODLL_FILEAREA=>$this->get_name(),
        	ASSIGNSUBMISSION_ONLINEPOODLL_WB_FILEAREA=>$this->get_name() . " whiteboard backimage");
    }

}


