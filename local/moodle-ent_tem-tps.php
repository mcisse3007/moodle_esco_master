<?php  // $Id: moodle-ent.php,v 0.2 2010/12/10 $


/* 
$libdir='/var/www/html/moodle/lib';
$dirroot='/var/www/html/moodle'; 
*/

require_once('../config.php');
//require_once($CFG->libdir.'/blocklib.php');
require_once('../lib/blocklib.php');
//require_once($CFG->dirroot.'/course/lib.php');
require_once('../course/lib.php');
//require_once($CFG->libdir.'pagelib.php');
require_once('../lib/pagelib.php');
// Modif DB 14/08//2012 : seule reference au fichier ci-apres
// et il pose pb : plantage ==> mis en commentaire
//require_once('../admin/settings/courses.php');

//$moodle_url="https://dsiwebtest.insa-rouen.fr/moodle";
//$moodle_url="http://moodle2015.tem-tsp.eu/my";
//$moodle_url="https://moodle2015.tem-tsp.eu/Shibboleth.sso/Login?SAMLDS=1&target=https://moodle2015.tem-tsp.eu/auth/shibboleth/index.php&entityID=https://idpmt.tem-tsp.eu/idp/shibboleth";
$moodle_url="https://lycees.netocentre.fr/moodle/moodle28/my";

// copie de la function course/lib/print_overview avec quelques modifications de mise en forme
function RUNN_print_overview($courses) {
	global $CFG, $USER;
	$htmlarray = array();
/* Modif DB 14/08//2012 : à etudier 
	if ($modules = get_records('modules')) {
		foreach ($modules as $mod) {
				if (file_exists(dirname(dirname(__FILE__)).'/mod/'.$mod->name.'/lib.php')) {
					include_once(dirname(dirname(__FILE__)).'/mod/'.$mod->name.'/lib.php');
					$fname = $mod->name.'_print_overview';
					if (function_exists($fname)) {
						$fname($courses,$htmlarray);
					}
				}
		}
	}
*/
	foreach ($courses as $course) {
		$linkcss = '';
		if (empty($course->visible)) {
				$linkcss = 'class="dimmed"';
		}
// Modif DB 14/08//2012 : suppression (brutale) des guillemets dans les titres car cela pose souci (liens non cliquables) : à améliorer 
	$course->fullname = str_replace('"', '', $course->fullname) ;
// Modif DB 14/08//2012 : ajout target=_blank 
		print('<dt><a  target=_blank title="'. format_string($course->fullname).'" '.$linkcss.' href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'">'. format_string
($course->fullname).'</a></dt>'."\n");
// Modif DB 14/08//2012 : 
/* inutile si le bloc sur les modules ci-dessus desactivé 
		if (array_key_exists($course->id,$htmlarray)) {
				foreach ($htmlarray[$course->id] as $modname => $html) {
					echo "<dd>".$html."</dd>\n";
				}
		}
*/
	}
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Moodle</title>
</head>
<body>
<div class="cours-moodle">
	<div class="portlet-section">
<?php
print('<a href="'.$moodle_url.'" target="_blank">Accéder directement à Moodle</a>');
print('<div align=right>Tutoriel de présentation de Moodle</div>');
print('<div align=right>(<a href="http://www-public.it-sudparis.eu/~domy/Moodle/Tutoriel/" target="_blank">français</a>');
print('&nbsp; - - - &nbsp; <a href="http://www-public.it-sudparis.eu/~domy/Moodle/English/" target="_blank">english</a>)</div>');
?>
		<div class="portlet-section-body">
			<h2 class="portlet-section-header">Mes cours Moodle (année 2015-2016)</h2>
			<dl>

<?php
$uid = $_GET['uid'];
//	$anuser = get_record("user","username", $uid);

//nouvelle version
global $DB;
$conditions = array("username" => $uid);
$anuser = $DB->get_record("user", $conditions);
//Modif EB 11/02/2015 : si utilisateur inconnu, guest utilisé
if(!$anuser){
	$conditions = array("username" => 'guest');
	$anuser = $DB->get_record("user", $conditions);
}
$mymoodlestr = get_string('mymoodle','my');

/// The main overview
$courses_limit = 21;
if (isset($CFG->mycoursesperpage)) {
	$courses_limit = $CFG->mycoursesperpage;
}

$morecourses = false;
if ($courses_limit > 0) {
	$courses_limit = $courses_limit + 1;
}
// Modif DB 14/08//2012 : sinon USER inconnu par enrol_get_my_courses
$USER = $anuser ;
//Nouvelle version
//	$courses = get_my_courses($anuser->id, 'visible DESC,sortorder ASC', '*', false, $courses_limit);
//$courses = enrol_get_my_courses($anuser->id, 'visible DESC,sortorder ASC', '*', false, $courses_limit);
// Modif DB 14/08//2012 : fonction avec 3 parametres max 
$courses = enrol_get_my_courses(NULL, 'visible DESC, fullname ASC') ;
//$courses = enrol_get_my_courses($anuser->id, 'visible DESC,sortorder ASC', , array('summary'));
$site = get_site();
if (($courses_limit > 0) && (count($courses) >= $courses_limit)) {
	//remove the 'marker' course that we retrieve just to see if we have more than $courses_limit
	array_pop($courses);
	$morecourses = true;
}
if (array_key_exists($site->id,$courses)) {
	unset($courses[$site->id]);
}
foreach ($courses as $c) {
	if (isset($USER->lastcourseaccess[$c->id])) {
			$courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
	}
	else {
			$courses[$c->id]->lastaccess = 0;
	}
}
if (empty($courses)) {
//Modif EB 11/02/2015 : print_simple_box obsolète
	//print_simple_box(get_string('nocourses','my'),'center');
	print('<div style="align: center">'.get_string('nocourses','my').'</div>');
}
else {
	RUNN_print_overview($courses);
}
// if more than 20 courses
if ($morecourses) {
	print('...<br /><a href="'.$moodle_url.'" target="_blank">Accès à la liste de tous les cours</a>');
}
print('<br /><br/><div align=right>Accéder aux <a href="http://moodle.tem-tsp.eu/mod/page/view.php?id=15535" target="_blank">archives</a> Moodle des années précédentes</div>');
?>
			</dl>
		</div><!-- portlet-section-body -->
	</div><!-- portlet-section -->
</div><!-- cours-moodle -->
</body>
</html>
