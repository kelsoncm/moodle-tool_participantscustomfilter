<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Filter manager for tool_participantscustomfilter.
 *
 * @package    tool_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_participantscustomfilter;

defined('MOODLE_INTERNAL') || die();

/**
 * Manages retrieval and application of custom profile field filters on the
 * participants page.
 */
class filter {

    /** @var int Course id. */
    protected int $courseid;

    /**
     * Constructor.
     *
     * @param int $courseid Course id.
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Return all user profile custom fields available in the system.
     *
     * @return array  Array of ['shortname' => string, 'name' => string].
     */
    public function get_custom_fields(): array {
        global $DB;

        $records = $DB->get_records('user_info_field', [], 'name ASC', 'id, shortname, name');
        $fields  = [];
        foreach ($records as $record) {
            $fields[] = [
                'shortname' => $record->shortname,
                'name'      => format_string($record->name),
            ];
        }
        return $fields;
    }

    /**
     * Check whether a given shortname corresponds to an existing profile field.
     *
     * @param  string $shortname
     * @return bool
     */
    public function is_valid_field(string $shortname): bool {
        global $DB;
        return $DB->record_exists('user_info_field', ['shortname' => $shortname]);
    }

    /**
     * Return participants of the course whose custom profile field value matches
     * (partial, case-insensitive) the given string.
     *
     * @param  string $fieldshortname  Shortname of the profile field to filter by.
     * @param  string $filtervalue     Value to search for (substring match).
     * @param  int    $page            0-based page number for pagination.
     * @param  int    $perpage         Results per page.
     * @return array  ['total' => int, 'users' => \stdClass[]]
     */
    public function get_filtered_participants(
        string $fieldshortname,
        string $filtervalue,
        int $page    = 0,
        int $perpage = 20
    ): array {
        global $DB;

        if (!$this->is_valid_field($fieldshortname)) {
            return ['total' => 0, 'users' => []];
        }

        $likevalue = '%' . $DB->sql_like_escape($filtervalue) . '%';

        // ENROL_INSTANCE_ENABLED = 0, ENROL_USER_ACTIVE = 0.
        // Only count/return participants with an active enrolment on an enabled
        // enrolment instance, consistent with Moodle's own participants listing.

        // Build a direct COUNT query so we do not need a wrapping sub-select.
        $countsql = "SELECT COUNT(DISTINCT u.id)
                       FROM {user}              u
                       JOIN {user_enrolments}   ue  ON ue.userid    = u.id
                                                    AND ue.status    = :activeue
                       JOIN {enrol}             e   ON e.id         = ue.enrolid
                                                    AND e.courseid   = :courseid_sub
                                                    AND e.status     = :activee
                       JOIN {user_info_field}   uif ON uif.shortname = :fieldshortname
                       JOIN {user_info_data}    uid ON uid.fieldid   = uif.id
                                                    AND uid.userid    = u.id
                      WHERE u.deleted = 0
                        AND " . $DB->sql_like('uid.data', ':filtervalue_sub', false);

        $baseparams = [
            'courseid_sub'    => $this->courseid,
            'fieldshortname'  => $fieldshortname,
            'filtervalue_sub' => $likevalue,
            'activeue'        => 0, // ENROL_USER_ACTIVE
            'activee'         => 0, // ENROL_INSTANCE_ENABLED
        ];

        $total = (int) $DB->count_records_sql($countsql, $baseparams);

        if ($total === 0) {
            return ['total' => 0, 'users' => []];
        }

        $selectsql = "SELECT DISTINCT u.id, u.firstname, u.lastname, u.email, u.username,
                             u.picture, u.imagealt, u.firstnamephonetic, u.lastnamephonetic,
                             u.middlename, u.alternatename
                        FROM {user}              u
                        JOIN {user_enrolments}   ue  ON ue.userid    = u.id
                                                     AND ue.status    = :activeue
                        JOIN {enrol}             e   ON e.id         = ue.enrolid
                                                     AND e.courseid   = :courseid_sub
                                                     AND e.status     = :activee
                        JOIN {user_info_field}   uif ON uif.shortname = :fieldshortname
                        JOIN {user_info_data}    uid ON uid.fieldid   = uif.id
                                                     AND uid.userid    = u.id
                       WHERE u.deleted = 0
                         AND " . $DB->sql_like('uid.data', ':filtervalue_sub', false) . "
                    ORDER BY u.lastname ASC, u.firstname ASC";

        $users = array_values(
            $DB->get_records_sql($selectsql, $baseparams, $page * $perpage, $perpage)
        );

        return ['total' => $total, 'users' => $users];
    }
}
