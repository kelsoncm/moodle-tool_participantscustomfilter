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
 * AMD module for tool_participantscustomfilter.
 *
 * Injects the server-rendered filter form (and, when a filter is active,
 * the filtered results panel) into the participants page DOM without any
 * additional AJAX round-trip.
 *
 * @module     tool_participantscustomfilter/filter
 * @copyright  2024 IFRN
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Ordered list of CSS selectors used to locate the participants table
 * wrapper in the DOM.  We try several selectors to be compatible with
 * different Moodle 4.x theme / page layouts.
 *
 * @type {string[]}
 */
const PARTICIPANTS_TABLE_SELECTORS = [
    '[data-region="participants-table"]',
    '[data-region="participants"]',
    '.participants-overview-table',
    '#participants-table',
    '.userenrolments',
    '#region-main .flexible',   // table_sql default class
    '#region-main table',       // generic fallback
];

/**
 * Find the first element in the DOM that matches any of the given selectors.
 *
 * @param  {string[]} selectors
 * @return {Element|null}
 */
const findFirst = (selectors) => {
    for (const sel of selectors) {
        const el = document.querySelector(sel);
        if (el) {
            return el;
        }
    }
    return null;
};

/**
 * Parse an HTML string and return the first root element.
 *
 * @param  {string} html
 * @return {Element}
 */
const parseHTML = (html) => {
    const tpl = document.createElement('template');
    tpl.innerHTML = html.trim();
    return tpl.content.firstElementChild;
};

/**
 * Initialise the filter.
 *
 * Called by $PAGE->requires->js_call_amd() with the data object built in
 * tool_participantscustomfilter_before_standard_html_head().
 *
 * @param {Object}  config
 * @param {string}  config.formhtml    Server-rendered filter form HTML.
 * @param {string}  config.resultshtml Server-rendered filtered results HTML
 *                                     (empty string when filter is not active).
 * @param {boolean} config.isfiltered  Whether a filter is currently active.
 */
export const init = (config) => {
    const {formhtml, resultshtml, isfiltered} = config;

    const inject = () => {
        // ----------------------------------------------------------------
        // Locate the target element that will be our insertion anchor.
        // ----------------------------------------------------------------
        const anchor = findFirst(PARTICIPANTS_TABLE_SELECTORS);
        if (!anchor) {
            // Participants table not yet in DOM; nothing to attach to.
            return;
        }

        const parent = anchor.parentNode;

        // ----------------------------------------------------------------
        // Inject filter form immediately before the participants table.
        // ----------------------------------------------------------------
        if (formhtml) {
            // Avoid injecting twice (e.g. if init() is called more than once).
            if (!document.getElementById('tool-participantscustomfilter-wrap')) {
                const formEl = parseHTML(formhtml);
                if (formEl) {
                    parent.insertBefore(formEl, anchor);
                }
            }
        }

        // ----------------------------------------------------------------
        // When a filter is active, inject the results panel right after
        // the filter form (still before the standard participants table
        // so users clearly see what the filtered view contains).
        // ----------------------------------------------------------------
        if (isfiltered && resultshtml) {
            if (!document.getElementById('tool-participantscustomfilter-results')) {
                const resultsEl = parseHTML(resultshtml);
                if (resultsEl) {
                    // Insert after the form wrapper (or before the table if
                    // the form was not injected).
                    const formWrap = document.getElementById('tool-participantscustomfilter-wrap');
                    const insertBefore = formWrap ? formWrap.nextSibling : anchor;
                    parent.insertBefore(resultsEl, insertBefore);
                }
            }
        }
    };

    // Run as soon as the DOM is interactive (handles both early and late calls).
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inject);
    } else {
        inject();
    }
};
