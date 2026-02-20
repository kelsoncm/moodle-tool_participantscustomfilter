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
 * Library / callback functions for local_participantscustomfilter.
 *
 * @package    local_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Hook: inject filter form and optional filtered results into the
 * participants page (enrol/index.php).
 *
 * This callback runs inside $OUTPUT->header() → standard_head_html().
 * At that point $OUTPUT is fully initialised and render_from_template()
 * is safe to call.  We render both pieces of HTML server-side so that
 * the AMD module only needs to move them into the right DOM position –
 * no additional AJAX round-trip is required.
 *
 * @return string  Empty string (required by the hook API); all content
 *                 is handed to the AMD module via js_call_amd().
 */
function local_participantscustomfilter_before_standard_html_head(): string {
    global $PAGE, $OUTPUT;

    // Guard: $PAGE->url is not always set at this point.
    if (empty($PAGE->url)) {
        return '';
    }

    // Only inject on the enrol / participants listing page.
    if (!$PAGE->url->compare(new \moodle_url('/enrol/index.php'), URL_MATCH_BASE)) {
        return '';
    }

    $courseid = (int) $PAGE->url->get_param('id');
    if ($courseid <= 0) {
        return '';
    }

    // Security: the viewing user must be able to view course participants.
    $context = \context_course::instance($courseid, IGNORE_MISSING);
    if (!$context || !has_capability('moodle/course:viewparticipants', $context)) {
        return '';
    }

    // Read (and sanitise) the filter parameters from the current URL.
    $filtercustomfield = optional_param('filtercustomfield', '', PARAM_ALPHANUMEXT);
    $filtervalue       = optional_param('filtervalue',       '', PARAM_TEXT);

    // Collect available custom profile fields.
    $filtermanager = new \local_participantscustomfilter\filter($courseid);
    $fields        = $filtermanager->get_custom_fields();

    // ----------------------------------------------------------------
    // Render the filter form via our plugin renderer.
    // ----------------------------------------------------------------
    /** @var local_participantscustomfilter_renderer $renderer */
    $renderer   = $PAGE->get_renderer('local_participantscustomfilter');
    $filterform = new \local_participantscustomfilter\output\filter_form(
        $courseid,
        $fields,
        $filtercustomfield,
        $filtervalue
    );
    $formhtml = $renderer->render_filter_form($filterform);

    // ----------------------------------------------------------------
    // If a filter is active, build the filtered results HTML.
    // ----------------------------------------------------------------
    $resultshtml = '';
    $isfiltered  = ($filtercustomfield !== '' && $filtervalue !== '');

    if ($isfiltered) {
        if ($filtermanager->is_valid_field($filtercustomfield)) {
            $filtered = $filtermanager->get_filtered_participants(
                $filtercustomfield,
                $filtervalue
            );

            $usersdata = [];
            foreach ($filtered['users'] as $user) {
                $profileurl = new \moodle_url('/user/view.php', [
                    'id'     => $user->id,
                    'course' => $courseid,
                ]);
                $usersdata[] = [
                    'id'         => $user->id,
                    'fullname'   => fullname($user),
                    'email'      => $user->email,
                    'username'   => $user->username,
                    'profileurl' => $profileurl->out(false),
                ];
            }

            $resultsdata = [
                'total'       => $filtered['total'],
                'hasusers'    => !empty($usersdata),
                'users'       => $usersdata,
                'courseid'    => $courseid,
                'filtervalue' => s($filtervalue),
            ];
            $resultshtml = $renderer->render_filtered_results($resultsdata);
        } else {
            // Invalid field – surface a visible warning.
            $notif = new \core\output\notification(
                get_string('invalidfield', 'local_participantscustomfilter'),
                \core\output\notification::NOTIFY_ERROR
            );
            $notif->set_show_closebutton(false);
            $resultshtml = $OUTPUT->render($notif);
        }
    }

    // ----------------------------------------------------------------
    // Pass the rendered HTML to the AMD module for DOM injection.
    // The AMD module waits for DOMContentLoaded and inserts the form
    // (and, when a filter is active, the results panel) in the correct
    // position relative to the participants table.
    // ----------------------------------------------------------------
    $PAGE->requires->js_call_amd(
        'local_participantscustomfilter/filter',
        'init',
        [[
            'formhtml'    => $formhtml,
            'resultshtml' => $resultshtml,
            'isfiltered'  => $isfiltered,
        ]]
    );

    return '';
}
