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
 * Plugin strings are defined here.
 *
 * @package     mod_discourse
 * @category    string
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'DisKurs';

// mod_form.php
$string['modulename'] = 'DisKurs';
$string['modulename_help'] = 'Die Aktivität DisKurs erlaubt die Durchführung einer besonderen Gruppendiskussion ... ';
$string['pluginadministration'] = 'Administration des DisKurses';
$string['phasecompletion'] = 'Phasen-Abschluss';
$string['mode_autoswitch'] = 'Modus "Automatischer Phasenwechsel" aktivieren';
$string['autoswitch'] = 'Die Phasen werden automatisch zu den im folgenden angegebenen Zeitpunkten gewechselt';
$string['deadlinephaseone'] = 'Abschluss der Einzelphase';
$string['deadlinephasetwo'] = 'Abschluss der 1. Gruppenphase';
$string['deadlinephasethree'] = 'Abschluss der 2. Gruppenphase';
$string['deadlinephasefour'] = 'Abschluss der Gemeinschaftsphase';
$string['phaseshints'] = 'Hinweistexte zu den Phasen';
$string['hintphaseone'] = 'Hinweis zur Einzelphase';
$string['hintphasetwo'] = 'Hinweis zur 1. Gruppenphase';
$string['hintphasethree'] = 'Hinweis zur 2. Gruppenphase';
$string['hintphasefour'] = 'Hinweis zur Gemeinschaftsphase';

// index.php
$string['modulenameplural'] = 'DisKurse';
$string['nonewmodules'] = 'Keine neuen DisKurse';

// lib.php
$string['deletealluserdata'] = 'Alle Benutzerdaten löschen';

// capabilities
$string['discourse:addinstance'] = 'Neuen DisKurs hinzufügen';
$string['discourse:viewdiscourseteacher'] = 'DisKurs als Lehrender ansehen';
$string['discourse:viewdiscoursestudent'] = 'DisKurs als Teilnehmer ansehen';
$string['discourse:editgrouptext'] = 'Gruppentext einreichen oder bearbeiten';
$string['discourse:switchphase'] = 'Diskurs-Phase umschalten';

// privacy
$string['privacy:metadata:discourse_participants'] = 'Enthält die persönlichen Daten aller DisKurs Teilnehmenden.';
$string['privacy:metadata:discourse_submissions'] = 'Enthält alle Daten zu DisKurs Einreichungen.';
$string['privacy:metadata:discourse_participants:discourse'] = 'ID des DisKurses des Teilnehmers';
$string['privacy:metadata:discourse_submissions:discourse'] = 'ID des DisKurses der Einreichung';
$string['privacy:metadata:core_message'] = 'Das DisKurs Plugin sendet Nachrichten an Benutzer und speichert deren Inhalte in der Datenbank.';

