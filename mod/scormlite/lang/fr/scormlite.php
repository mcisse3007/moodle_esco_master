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
 * Strings for component 'scormlite', language 'fr'
 *
 */

// Plugin strings

$string['scormlite'] = 'SCORM Lite';
$string['modulename'] = 'Module SCORM Lite';
$string['modulename_help'] = 'Un module SCORM Lite est un package SCORM 2004 simplifié, contenant un seul SCO, sans manifest.';
$string['modulenameplural'] = 'Modules SCORM Lite';
$string['pluginadministration'] = 'Administration SCORM Lite';
$string['pluginname'] = 'SCORM Lite';
$string['page-mod-scormlite-x'] = 'Toutes les pages du module SCORM Lite';
$string['dnduploadscormlite'] = 'Ajouter un module SCORM Lite';

// Permissions

$string['scormlite:reviewmycontent'] = 'Mode corrigé sur mes contenus';
$string['scormlite:reviewothercontent'] = 'Mode corrigé sur les contenus des autres';
$string['scormlite:viewmyreport'] = 'Voir mes rapports';
$string['scormlite:viewotherreport'] = 'Voir les rapports des autres';
$string['scormlite:addinstance'] = 'Ajouter un nouveau module SCORM Lite';

// Edit page (incl. module settings)

// General settings
$string['general'] = 'Données générales';
$string['title'] = 'Titre';
$string['code'] = 'Code';
$string['code_help'] = 'Le code est utilisé comme un nom court pour identifier ce contenu dans les rapports.';
$string['package'] = 'Package';
$string['package_help'] = 'Le package est un fichier zip qui contient un contenu SCORM Lite (mono-SCO avec fichier index.html à la racine du package).';
// Availability
$string['timerestrict'] = 'Limiter les réponses à cette période';
$string['manualopen'] = 'Disponibilité';
$string['manualopendesc'] = 'Ce paramètre permet de forcer l\'ouverture ou la fermeture de l\'activité, sans tenir compte des dates d\'ouverture et de fermeture.';
$string['manualopendates'] = 'Utiliser les dates';
$string['manualopenopen'] = 'Ouvrir';
$string['manualopenclose'] = 'Fermer';
$string['manualopenauto'] = 'Automatique';
$string['manualopenterminate'] = 'Terminer';
$string['scormopen'] = 'De';
$string['scormclose'] = 'A';
// Advanced settings
$string['othersettings'] = 'Paramètres complémentaires';
$string['maxtime'] = 'Temps maximum (minutes)';
$string['maxtimedesc'] = 'Temps maximum autorisé pour passer le test. Le temps doit être exprimé en minutes (ex. 60 pour un temps maximum d\'une heure). 0 signifie qu\'il n\'y a pas de limite de temps.';
$string['maxtime_help'] = $string['maxtimedesc'];
$string['passingscore'] = 'Seuil de réussite (%)';
$string['passingscoredesc'] = 'Le seuil de réussite est le score minimum que l\'apprenant doit atteindre pour réussir le test. Vous devez entrer un entier entre 1 et 100.';
$string['passingscore_help'] = $string['passingscoredesc'];
$string['display'] = 'Afficher dans';
$string['displaydesc'] = 'Ce paramètre permet d\'indiquer comment ouvrir le contenu.';
$string['currentwindow'] = 'Fenêtre courante';
$string['popup'] = 'Nouvelle fenêtre';
$string['displaychrono'] = 'Afficher le chronomètre';
$string['displaychronodesc'] = 'Afficher un chronomètre dans le contenu. Le contenu importé doit avoir été conçu pour supporter ce paramètre.';
$string['displaychrono_help'] = $string['displaychronodesc'];
// Attempts
$string['maximumattempts'] = 'Nombre de tentatives';
$string['maximumattempts_help'] = 'Ce paramètre permet de limiter le nombre de tentatives.';
$string['maximumattemptsdesc'] = 'Nombre maximum de tentatives par défaut pour les activités SCORMLite';
$string['whatgrade'] = 'Notation des tentatives';
$string['whatgrade_help'] = 'Lorsque plusieurs tentatives sont autorisées, ce paramètre précise quel score doit être retenu : le plus élevé, une moyenne, le premier, le dernier.';
$string['whatgradedesc'] = 'Mode de notation par défaut pour les activités SCORMLite.';
$string['nolimit'] = 'Tentatives illimitées';
$string['attempt1'] = '1 tentative';
$string['attemptsx'] = '{$a} tentatives';
$string['highestattempt'] = 'Meilleur score';
$string['firstattempt'] = 'Premier score';
$string['lastattempt'] = 'Dernier score';
// Colors
$string['scorelessthan'] = 'Score <';
$string['scoreupto'] = 'Score <=';
$string['colors'] = 'Couleurs de reporting';
$string['colorsdesc'] = 'Couleurs à appliquer pour l\'affichage des scores dans les rapports. Chaque valeur indique le score au dessous duquel la couleur s\'applique.';
$string['colors_help'] = $string['colorsdesc'];
// Errors
$string['notvalidpackage'] = 'Ce fichier n\'est pas un package SCORM Lite valide !';
$string['notvalidmaxtime'] = 'Vous devez entrer une valeur positive';
$string['notvalidpassingscore'] = 'Vous devez entrer une valeur entre 1 et 100';
$string['notvalidtresholdscore'] = 'Vous devez entrer une valeur entre 0 et 100';
// Files
$string['areacontent'] = 'Contenus';
$string['areapackage'] = 'Packages';

// Edit settings
$string['displayrank'] = 'Afficher le classement';
$string['displayrankdesc'] = 'Afficher le classement des élèves dans les rapports.';


// Playing page

// Tabs
$string['tabplay'] = 'Contenu';
$string['tabreport'] = 'Rapport';
// SCORM status
$string['notattempted'] = 'Non commencé';
$string['incomplete'] = 'Non terminé';
$string['completed'] = 'Terminé';
$string['passed'] = 'Réussi';
$string['failed'] = 'Echec';
$string['suspended'] = 'Suspendu';
// Status labels
$string['score'] = 'Score';
$string['started'] = 'Tentative commencée le';
$string['first'] = 'Premier accès le';
$string['last'] = 'Dernier accès le';
// Availability
$string['notautoopen'] = 'Cette activité n\'est pas disponible';
$string['notopen'] = 'Cette activité n\'est pas disponible';
$string['notopenyet'] = 'Cette activité n\'est pas disponible avant le {$a}';
$string['expired'] = 'Cette activité est finie depuis le {$a} et n\'est plus disponible';
// Attempts
$string['noattemptsallowed'] = 'Nombre de tentatives autorisées';
$string['noattemptsmade'] = 'Nombre de tentatives terminées';
$string['attempt'] = 'tentative';
$string['attemptcap'] = 'Tentative';
$string['attemptscap'] = 'Tentatives';
$string['newattempt'] = 'Nouvelle Tentative';
// Actions
$string['review'] = 'Mode corrigé';
$string['start'] = 'Commencer';
$string['restart'] = 'Recommencer';
$string['resume'] = 'Reprendre';
// Content
$string['activityloading'] = 'Vous allez être automatiquement redirigé vers l\'activité...';
$string['activitypleasewait'] = 'Activité en cours de chargement, merci de patienter...';
$string['popupmessage'] = "Le contenu doit s'ouvrir dans une nouvelle fenêtre. 
Si ce n'est pas le cas, merci de vérifier les paramètres de blocage des fenêtres de votre navigateur. 
Merci de ne pas naviguer depuis cette page tant que la fenêtre du contenu est ouverte.
";
$string['recovery'] = 'La dernière session de ce contenu s\'est finie anormalement et va être restaurée.';
$string['notallowed'] = 'Vous n\'êtes pas autorisé à faire cela !';
$string['accessdenied'] = 'Vous n\'êtes pas autorisé à consulter ce contenu !';
$string['exitactivity'] = 'Sortir de l\'activité';
$string['exitcontent'] = 'Sortir du contenu';

// Report

$string['learner'] = 'Apprenant';
$string['status'] = 'Etat';
$string['time'] = 'Temps';
$string['totaltime'] = 'Temps';
$string['action'] = 'Action';
$string['groupaverage'] = 'Moyenne des apprenants du groupe';
$string['noreportdata'] = 'Il n\'y a pas de données pour ce rapport.';
$string['nogroupingdata'] = 'Il n\'y a pas de groupe pour ce rapport.';
$string['nousergroupingdata'] = 'Il n\'y a pas de groupe pour cet utilisateur : {$a}.';
$string['noactivitygrouping'] = 'Il n\'y a pas de groupe associé à cette activité.';
$string['selectgrouping'] = 'Merci de sélectionner un groupe pour afficher ce rapport.';
$string['averagescore_short'] = 'Moy.';
$string['averagescore'] = 'Moyenne';
$string['rank'] = 'Rang';
$string['activityreport'] = 'Rapport d\'activité';
$string['select'] = '-- Sélectionner --';
$string['learnerresults'] = 'Résultats de l\'apprenant <em>{$a}</em>';
$string['groupresults'] = 'Résultats du groupe  <em>{$a}</em>';
$string['groupresults_nostyle'] = 'Résultats du groupe {$a}';
$string['groupprogress'] = 'Progression du groupe  <em>{$a}</em>';
$string['progress'] = 'Progression';
$string['exporthtml'] = 'Export HTML';
$string['exportcsv'] = 'Export CSV';
$string['deletealltracks'] = 'Supprimer toutes les traces';
$string['legend'] = 'Légende';
$string['legendR'] = 'R = Remédiation';
$string['legendPC'] = 'PC = Progress Check';
$string['legendFE'] = 'FE = Final Exam';
// Attempts
$string['highestattemptdesc'] = 'Seules les meilleurs tentatives sont affichées dans le tableau suivant.';
$string['firstattemptdesc'] = 'Seules les premières tentatives sont affichées dans le tableau suivant.';
$string['lastattemptdesc'] = 'Seules les dernières tentatives sont affichées dans le tableau suivant.';
$string['deleteattemps'] = 'Supprimer toutes les tentatives des utilisateurs sélectionnés';
$string['deleteattempsconfirm'] = 'Souhaitez-vous réellement supprimer toutes les tentatives des utilisateurs sélectionnés ?';
$string['deleteattempsno'] = 'Vous devez fermer cette activité pour pouvoir supprimer des tentatives.';
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


 

