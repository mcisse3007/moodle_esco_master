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

defined('MOODLE_INTERNAL') || die();


function xmldb_scormlite_upgrade($oldversion) {

    global $CFG, $DB;
    $dbman = $DB->get_manager();

	// SCO table
	if ($oldversion < 2012010500) {
		$scoes = $DB->get_records('scormlite_scoes');
		foreach ($scoes as $sco) {
			$colors = json_decode("[$sco->colors]");
			if (is_object(reset($colors))) {
				$new_colors = array();
				foreach ($colors as $color) {
					$new_colors[] = $color->lt;
				}
				$sco->colors = implode(',', $new_colors);
				$DB->update_record('scormlite_scoes', $sco);
			}
		}
		$jsoncolors = '{"lt":50, "color":"#faa"}, {"lt":65, "color":"#fca"}, {"lt":75, "color":"#ffa"}, {"lt":101,"color":"#afa"}';
		$DB->set_field('config_plugins', 'value', $jsoncolors, array('plugin'=>'scormlite', 'name'=>'colors'));
	}

    // Adding attempt fields to scormlite_scoes table
    if ($oldversion < 2012112901) {
        $table = new xmldb_table('scormlite_scoes');

        $field = new xmldb_field('maxattempt', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '1', 'popup');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('whatgrade', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'maxattempt');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2012112901, 'scormlite');
    }

	return true;
}

