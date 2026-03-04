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
 * Strings em Português do Brasil para tool_participantscustomfilter.
 *
 * @package    tool_participantscustomfilter
 * @copyright  2024 IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Nome do plugin exibido na interface administrativa.
$string['pluginname'] = 'Filtro de participantes por campo personalizado';

// Strings do formulário de filtro.
$string['filterbyprofilefield'] = 'Filtrar por campo de perfil';
$string['profilefield']         = 'Campo de perfil';
$string['selectfield']          = '— Selecione um campo —';
$string['value']                = 'Valor';
$string['filtervalue']          = 'Digite um valor…';
$string['filter']               = 'Filtrar';
$string['clearfilter']          = 'Limpar filtro';

// Strings da área de resultados.
$string['filteredresults'] = 'Participantes filtrados';
$string['noresults']       = 'Nenhum participante encontrado com os critérios selecionados.';

// Strings de erro / informação.
$string['nocustomfields'] = 'Nenhum campo de perfil personalizado foi configurado neste site.';
$string['invalidfield']   = 'O campo de perfil selecionado não existe. Por favor, escolha um campo válido.';

// Strings de privacidade (este plugin não armazena dados pessoais).
$string['privacy:metadata'] = 'O plugin Filtro de participantes por campo personalizado não armazena nenhum dado pessoal.';
