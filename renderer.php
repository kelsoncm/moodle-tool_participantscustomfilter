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
 * Renderer for tool_participantscustomfilter.
 *
 * @package    tool_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer class for tool_participantscustomfilter.
 *
 * Provides methods to render the custom-field filter form and the
 * filtered results table that are injected into the participants page.
 */
class tool_participantscustomfilter_renderer extends plugin_renderer_base {

    /**
     * Render the custom profile field filter form.
     *
     * @param  \tool_participantscustomfilter\output\filter_form $filterform
     * @return string  Rendered HTML.
     */
    public function render_filter_form(
        \tool_participantscustomfilter\output\filter_form $filterform
    ): string {
        $data = $filterform->export_for_template($this);
        return $this->render_from_template(
            'tool_participantscustomfilter/participants_filter',
            $data
        );
    }

    /**
     * Render the filtered participants results.
     *
     * @param  array $data  Template context data (total, hasusers, users, …).
     * @return string  Rendered HTML.
     */
    public function render_filtered_results(array $data): string {
        return $this->render_from_template(
            'tool_participantscustomfilter/filtered_results',
            $data
        );
    }
}
