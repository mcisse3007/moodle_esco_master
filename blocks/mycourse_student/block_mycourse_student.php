<?php
require_once($CFG->dirroot.'/lib/weblib.php');
require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/lib/ent.php');

class block_mycourse_student extends block_base {
	/**
	* block initializations
	*/
	public function init() {
		$this->title   = get_string('pluginname', 'block_mycourse_student');
	}
	
	function specialization() {
		// At this point, $this->instance and $this->config are available
		// for use. We can now change the title to whatever we want.
		$this->title = "Mes Cours";
	}
	
	public function get_content() {
		global $USER, $CFG,$DB;
		if ($this->content !== null) {
			return $this->content;
		}
	
		$this->content         =  new stdClass;
		$this->content->text   = '';
		$this->content->footer = '';
		
		
		$content = array();
		
		$courses_limit = 21;
		// FIXME: this should be a block setting, rather than a global setting
		if (isset($CFG->mycoursesperpage)) {
			$courses_limit = $CFG->mycoursesperpage;
		}
		
		$morecourses = false;
		if ($courses_limit > 0) {
			$courses_limit = $courses_limit + 1;
		}
		
		$courses = enrol_get_my_courses('id, shortname, summary', 'visible DESC,sortorder ASC', $courses_limit);
		
		$site = get_site();
		$course = $site;
		$sql = '';
		$listeCoursEleve = array();
		foreach ($courses as $c) {
			if (isset($USER->lastcourseaccess[$c->id])) {
				$courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
			} else {
				$courses[$c->id]->lastaccess = 0;
			}
			$sql .= " AND ".$c->id." <> course.id";
			$context = get_context_instance(CONTEXT_COURSE,$c->id);
			// roleid = 5 => correspond a l'eleve
			if($DB->record_exists('role_assignments', array('userid' => $USER->id,'contextid' => $context->id,'roleid' => 5 ))){
				array_push($listeCoursEleve,$c);
			}
			// roleid = 9 => correspond a l'inspecteur pédagogique
			if($DB->record_exists('role_assignments', array('userid' => $USER->id,'contextid' => $context->id,'roleid' => 9 ))){
				array_push($listeCoursEleve,$c);
			}
		}
		$sql .= " and (roleid = '9' or roleid = '5')";
		$listeCourses = enrol_get_my_courses_assignements($USER->id,$sql);
		foreach($listeCourses as $c){
			array_push($listeCoursEleve,$c);
		}
		
		if (empty($listeCoursEleve)) {
			$content[] = "Vous n'êtes inscrit à aucun cours";
		} else {
			ob_start();
		
			require_once $CFG->dirroot."/course/lib.php";

			foreach($listeCoursEleve as $cours){
				print_course($cours);
			}
			
		
			$content[] = ob_get_contents();
			ob_end_clean();
		}
		
		$this->content->text = implode($content);
		/* Ajout d'un bouton pour montrer/cacher le contenu des cours et la liste des enseignants */
        $this->content->text .= "<script type=\"text/javascript\">
YUI().use('node', function(Y){
    var handleClickSummary = function(e) {
        if(e.currentTarget.hasClass('plus')){
            e.currentTarget.removeClass('plus').addClass('minus');
            e.currentTarget.ancestor('.coursebox').one('.summary').addClass('unfolded').removeClass('folded');
        } else {
            e.currentTarget.removeClass('minus').addClass('plus');
            e.currentTarget.ancestor('.coursebox').one('.summary').addClass('folded').removeClass('unfolded');
        }
    };
    Y.on('click', handleClickSummary, '.block_mycourse_student .summary_reply.fold_reply');

    var handleClickTeachers = function(e) {
        if(e.currentTarget.hasClass('plus')){
            e.currentTarget.removeClass('plus').addClass('minus');
            e.currentTarget.ancestor('.coursebox').one('.teachers').addClass('unfolded').removeClass('folded');
        } else {
            e.currentTarget.removeClass('minus').addClass('plus');
            e.currentTarget.ancestor('.coursebox').one('.teachers').addClass('folded').removeClass('unfolded');
        }
    };
    Y.on('click', handleClickTeachers, '.block_mycourse_student .teachers_reply.fold_reply');
});
</script>";
		return $this->content;
	}
	
	/**
	* allow more than one instance of the block on a page
	*
	* @return boolean
	*/
	public function instance_allow_multiple() {
		//allow more than one instance on a page
		return false;
	}
	
	/**
	* allow the block to have a configuration page
	*
	* @return boolean
	*/
	public function has_config() {
		return false;
	}
	
	
	/**
	* locations where block can be displayed
	*
	* @return array
	*/
	public function applicable_formats() {
		return array('my-index'=>true);
	}
	

	
}
?>	
