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
 * English language strings for local_participantscustomfilter.
 *
 * @package    local_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name shown in the admin interface.
$string['pluginname'] = 'Participants custom field filter';

// Filter form strings.
$string['filterbyprofilefield'] = 'Filter by profile field';
$string['profilefield']         = 'Profile field';
$string['selectfield']          = '— Select a field —';
$string['value']                = 'Value';
$string['filtervalue']          = 'Type a value…';
$string['filter']               = 'Filter';
$string['clearfilter']          = 'Clear filter';

// Results area strings.
$string['filteredresults'] = 'Filtered participants';
$string['noresults']       = 'No participants found matching the selected criteria.';

// Error / info strings.
$string['nocustomfields'] = 'No custom profile fields have been configured for this site.';
$string['invalidfield']   = 'The selected profile field does not exist. Please choose a valid field.';

// Privacy / capability strings (no personal data is stored by this plugin).
$string['privacy:metadata'] = 'The Participants custom field filter plugin does not store any personal data.';
