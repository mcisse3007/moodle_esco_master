<?php
// This file is part of Moodle - http://moodle.org/.
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

namespace mod_dataform\pluginbase;

/**
 * @package mod_dataform
 * @copyright 2013 Itamar Tzadok
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Dataform notification form helper
 */
class dataformnotificationform_helper extends  dataformruleform_helper {

    /**
     *
     */
    public static function notification_definition($mform, $dataformid, $prefix = null) {
        global $DB;

        $paramtext = (!empty($CFG->formatstringstriptags) ? PARAM_TEXT : PARAM_CLEAN);

        // -------------------------------------------------------------------------------
        $mform->addElement('header', 'messagehdr', get_string('message', 'message'));
        $mform->setExpanded('messagehdr');

        // Message type
        $options = array(
            0 => get_string('notification', 'dataform'),
            1 => get_string('conversation', 'dataform'),
        );
        $mform->addElement('select', $prefix. 'messagetype', get_string('type', 'dataform'), $options);

        // Subject
        $mform->addElement('text', $prefix. 'subject', get_string('subject', 'dataform'));
        $mform->setType($prefix. 'subject', $paramtext);

        // Message
        $mform->addElement('textarea', $prefix. 'message', get_string('message', 'dataform'));
        $mform->setType($prefix. 'message', $paramtext);

        // Format
        $options = array(
            FORMAT_PLAIN => get_string('formatplain'),
            FORMAT_HTML => get_string('formathtml'),
        );
        $mform->addElement('select', $prefix. 'messageformat', get_string('format'), $options);

        // Sender: Entry author, manager
        // -------------------------------------------------------------------------------
        $mform->addElement('header', 'senderhdr', get_string('from'));
        $mform->setExpanded('senderhdr');

        $admin = get_admin();
        $options = array(
            \core_user::NOREPLY_USER => get_string('noreply', 'dataform'),
            \core_user::SUPPORT_USER => get_string('supportcontact', 'admin'),
            $admin->id => get_string('admin'),
            'author' => get_string('author', 'dataform'),
            'event' => get_string('event', 'dataform'),
        );
        $mform->addElement('select', $prefix. 'sender', get_string('from'), $options);

        // Recipient
        // -------------------------------------------------------------------------------
        $mform->addElement('header', 'recipientshdr', get_string('to'));
        $mform->setExpanded('recipientshdr');

        // Admin
        $mform->addElement('advcheckbox', $prefix. 'recipientadmin', get_string('admin'));
        // Support
        $mform->addElement('advcheckbox', $prefix. 'recipientsupport', get_string('supportcontact', 'admin'));
        // Entry author
        $mform->addElement('advcheckbox', $prefix. 'recipientauthor', get_string('author', 'dataform'));
        // Role (mod/dataform:notification permission in context)
        $mform->addElement('advcheckbox', $prefix. 'recipientrole', get_string('role'));
        // Username (comma delimited)
        $mform->addElement('text', $prefix. 'recipientusername', get_string('username'));
        $mform->setType($prefix. 'recipientusername', $paramtext);
        // Email (comma delimited)
        $mform->addElement('text', $prefix. 'recipientemail', get_string('email'));
        $mform->setType($prefix. 'recipientemail', $paramtext);

    }

    /**
     *
     */
    public static function notification_validation($data, $files, $prefix = null) {
        $errors = array();

        // Must have a recipient
        if (empty($data[$prefix.'recipientadmin'])
                and empty($data[$prefix.'recipientsupport'])
                and empty($data[$prefix.'recipientauthor'])
                and empty($data[$prefix.'recipientrole'])
                and empty($data[$prefix.'recipientusername'])
                and empty($data[$prefix.'recipientemail'])) {
            $errors[$prefix.'recipientadmin'] = get_string('err_required', 'form');
        }

        return $errors;
    }
}
