<?php

/**
 * LDAP enrolment plugin admin setting classes
 *
 * @package    enrol
 * @subpackage ldap
 * @author     Iñaki Arenaza
 * @copyright  2010 Iñaki Arenaza <iarenaza@eps.mondragon.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class admin_setting_simpleldap_rolemapping extends admin_setting {

    /**
     * Constructor: uses parent::__construct
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting default value for the setting (actually unused)
     */
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting);
    }

    /**
     * Returns the current setting if it is set
     *
     * @return mixed null if null, else an array
     */
    public function get_setting() {
        $roles = get_all_roles();
        $result = array();
        foreach ($roles as $role) {
            $contexts = $this->config_read('contexts_role'.$role->id);
            $memberattribute = $this->config_read('memberattribute_role'.$role->id);
            $result[] = array('id' => $role->id,
                              'name' => $role->name,
                              'contexts' => $contexts,
                              'memberattribute' => $memberattribute);
        }
        return $result;
    }

    /**
     * Saves the setting(s) provided in $data
     *
     * @param array $data An array of data, if not array returns empty str
     * @return mixed empty string on useless data or success, error string if failed
     */
    public function write_setting($data) {
        if(!is_array($data)) {
            return ''; // ignore it
        }

        $result = '';
        foreach ($data as $roleid => $data) {
            if (!$this->config_write('contexts_role'.$roleid, trim($data['contexts']))) {
                $return = get_string('errorsetting', 'admin');
            }
            if (!$this->config_write('memberattribute_role'.$roleid, moodle_strtolower(trim($data['memberattribute'])))) {
                $return = get_string('errorsetting', 'admin');
            }
        }
        return $result;
    }

    /**
     * Returns XHTML field(s) as required by choices
     *
     * Relies on data being an array should data ever be another valid vartype with
     * acceptable value this may cause a warning/error
     * if (!is_array($data)) would fix the problem
     *
     * @todo Add vartype handling to ensure $data is an array
     *
     * @param array $data An array of checked values
     * @param string $query
     * @return string XHTML field
     */
    public function output_html($data, $query='') {
        $return  = '<div style="float:left; width:auto; margin-right: 0.5em;">';
        $return .= '<div style="height: 2em;">'.get_string('roles', 'role').'</div>';
        foreach ($data as $role) {
            $return .= '<div style="height: 2em;">'.s($role['name']).'</div>';
        }
        $return .= '</div>';

        $return .= '<div style="float:left; width:auto; margin-right: 0.5em;">';
        $return .= '<div style="height: 2em;">'.get_string('contexts', 'enrol_ldap').'</div>';
        foreach ($data as $role) {
            $contextid = $this->get_id().'['.$role['id'].'][contexts]';
            $contextname = $this->get_full_name().'['.$role['id'].'][contexts]';
            $return .= '<div style="height: 2em;"><input type="text" size="40" id="'.$contextid.'" name="'.$contextname.'" value="'.s($role['contexts']).'"/></div>';
        }
        $return .= '</div>';

        $return .= '<div style="float:left; width:auto; margin-right: 0.5em;">';
        $return .= '<div style="height: 2em;">'.get_string('memberattribute', 'enrol_ldap').'</div>';
        foreach ($data as $role) {
            $memberattrid = $this->get_id().'['.$role['id'].'][memberattribute]';
            $memberattrname = $this->get_full_name().'['.$role['id'].'][memberattribute]';
            $return .= '<div style="height: 2em;"><input type="text" size="15" id="'.$memberattrid.'" name="'.$memberattrname.'" value="'.s($role['memberattribute']).'"/></div>';
        }
        $return .= '</div>';
        $return .= '<div style="clear:both;"></div>';

        return format_admin_setting($this, $this->visiblename, $return,
                                    $this->description, true, '', '', $query);
    }
}
