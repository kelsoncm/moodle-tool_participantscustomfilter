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
 * Renderable filter form for local_participantscustomfilter.
 *
 * @package    local_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_participantscustomfilter\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use moodle_url;

/**
 * Renderable for the participants custom-field filter form.
 */
class filter_form implements renderable, templatable {

    /** @var int Course id. */
    protected int $courseid;

    /** @var array Available custom profile fields [['shortname'=>…,'name'=>…], …]. */
    protected array $fields;

    /** @var string Currently selected field shortname. */
    protected string $currentfield;

    /** @var string Currently active filter value. */
    protected string $currentvalue;

    /**
     * Constructor.
     *
     * @param int    $courseid     Course id.
     * @param array  $fields       Available custom profile fields.
     * @param string $currentfield Currently selected field shortname.
     * @param string $currentvalue Currently active filter value.
     */
    public function __construct(
        int    $courseid,
        array  $fields,
        string $currentfield = '',
        string $currentvalue = ''
    ) {
        $this->courseid     = $courseid;
        $this->fields       = $fields;
        $this->currentfield = $currentfield;
        $this->currentvalue = $currentvalue;
    }

    /**
     * Export data for the Mustache template.
     *
     * @param  renderer_base $output Renderer.
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        $fieldsdata = [];
        foreach ($this->fields as $field) {
            $fieldsdata[] = [
                'shortname' => $field['shortname'],
                'name'      => $field['name'],
                'selected'  => ($field['shortname'] === $this->currentfield),
            ];
        }

        $actionurl = new moodle_url('/enrol/index.php', ['id' => $this->courseid]);
        $clearurl  = new moodle_url('/enrol/index.php', ['id' => $this->courseid]);

        return [
            'courseid'     => $this->courseid,
            'fields'       => $fieldsdata,
            'hasfields'    => !empty($fieldsdata),
            'currentfield' => $this->currentfield,
            'currentvalue' => $this->currentvalue,
            'isfiltered'   => $this->currentfield !== '' && $this->currentvalue !== '',
            'actionurl'    => $actionurl->out(false),
            'clearurl'     => $clearurl->out(false),
        ];
    }
}
