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
 * Activity publicprivate condition.
 *
 * @package availability_publicprivate
 * @copyright 2015 UC Regents
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_publicprivate;

defined('MOODLE_INTERNAL') || die();

define('PUBLICPRIVATE_PRIVATE', 1);
define('PUBLICPRIVATE_PUBLIC', 0);

/**
 * Activity publicprivate condition.
 *
 * @package availability_publicprivate
 * @copyright 2015 UC Regents
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition extends \core_availability\condition {
    /** @var int Expected publicprivate type */
    protected $expectedpublicprivate;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data structure from JSON decode
     * @throws \coding_exception If invalid data structure.
     */
    public function __construct($structure) {
        // Get expected publicprivate.
        if (isset($structure->status) && in_array($structure->status,
                array(PUBLICPRIVATE_PUBLIC, PUBLICPRIVATE_PRIVATE))) {
            $this->expectedpublicprivate = $structure->status;
        } else {
            throw new \coding_exception('Missing or invalid ->status for publicprivate condition');
        }
    }

    public function save() {
        return (object)array('type' => 'publicprivate', 
            'status' => $this->expectedpublicprivate);
    }

    /**
     * Returns a JSON object which corresponds to a condition of this type.
     *
     * Intended for unit testing, as normally the JSON values are constructed
     * by JavaScript code.
     *
     * @param int $expectedpublicprivate Expected publicprivate value (COMPLETION_xx)
     * @return stdClass Object representing condition
     */
    public static function get_json($expectedpublicprivate) {
        return (object)array('type' => 'publicprivate',
            'status' => (int)$expectedpublicprivate);
    }

    public function is_available($not, \core_availability\info $info, $grabthelot, $userid) {

        $allow = true;
        if ($this->expectedpublicprivate == PUBLICPRIVATE_PRIVATE) {
            $modinfo = $info->get_modinfo();
            $course = $modinfo->get_course();
            $context = \context_course::instance($course->id);
            $allow = is_enrolled($context, $userid, '', true) ||
                    has_capability('moodle/course:view', $context);
        }

        if ($not) {
            $allow = !$allow;
        }

        return $allow;
    }

    public function get_description($full, $not, \core_availability\info $info) {
        // Get name for module.
        $modinfo = $info->get_modinfo();
        if (!isset($this->expectedpublicprivate)) {
            $modname = get_string('missing', 'availability_publicprivate');
        }

//        // Work out which lang string to use.
//        if ($not) {
//            // Convert NOT strings to use the equivalent where possible.
//            switch ($this->expectedpublicprivate) {
//                case COMPLETION_INCOMPLETE:
//                    $str = 'requires_' . self::get_lang_string_keyword(COMPLETION_COMPLETE);
//                    break;
//                case COMPLETION_COMPLETE:
//                    $str = 'requires_' . self::get_lang_string_keyword(COMPLETION_INCOMPLETE);
//                    break;
//                default:
//                    // The other two cases do not have direct opposites.
//                    $str = 'requires_not_' . self::get_lang_string_keyword($this->expectedpublicprivate);
//                    break;
//            }
//        } else {
//            $str = 'requires_' . self::get_lang_string_keyword($this->expectedpublicprivate);
//        }

        if ($this->expectedpublicprivate === PUBLICPRIVATE_PRIVATE) {
            $str = 'requires_private';
        } else {
            $str = 'requires_public';
        }

        return get_string($str, 'availability_publicprivate');
    }

    protected function get_debug_string() {
        return 'expectedpublicprivate = ' . $this->expectedpublicprivate;
    }

}
