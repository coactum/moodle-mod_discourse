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
$string['usedeadlines'] = 'Deadlines für Phasen nutzen';
$string['modeautoswitch'] = 'Modus "Automatischer Phasenwechsel" aktivieren';
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

// locallib.php
$string['groupingdescription'] = 'Gruppierung für die DisKurs-Aktivität {$a}';
$string['phaseone'] = 'Einzelphase';
$string['phasetwo'] = '1. Gruppenphase';
$string['phasethree'] = '2. Gruppenphase';
$string['phasefour'] = 'Gemeinschaftsphase';
$string['groupfor'] = 'Gruppe für {$a}';
$string['group'] = 'Gruppe';

// discourse_view.mustache
$string['activephase'] = 'Aktive Phase';
$string['switchto'] = 'Wechseln zu';
$string['hint'] = 'Hinweis';
$string['activesince'] = 'Aktiv seit';
$string['activefrom'] = 'Aktiv ab';
$string['openallgroups'] = 'Alle Gruppen der {$a} öffnen';
$string['opengroup'] = 'Gruppe {$a} maximieren';
$string['closegroup'] = 'Gruppe {$a} minimieren';
$string['groupparticipants'] = 'TeilnehmerInnen';
$string['submission'] = 'Einreichung';
$string['nosubmission'] = 'Noch keine Einreichung';
$string['submissionreceived'] = 'Einreichung abgegeben';
$string['nogroups'] = 'Keine Gruppen vorhanden';

// view.php
$string['view'] = 'Übersicht';

// groupview.php
$string['groupview'] = 'Gruppenansicht';
$string['submissionstate'] = 'Status';
$string['submittet'] = 'Abgegeben am';
$string['notsubmittet'] = 'Noch nicht abgegeben';
$string['updated'] = 'Aktualisiert am';
$string['submitsubmission'] = 'Einreichung abgeben';
$string['editsubmission'] = 'Einreichung bearbeiten';
$string['currentversion'] = 'Aktuelle Version';
$string['submissioncontent'] = 'Inhalt der Einreichung';
$string['errfilloutfield'] = 'Bitte Feld ausfüllen';

// capabilities
$string['discourse:addinstance'] = 'Neuen DisKurs hinzufügen';
$string['discourse:viewdiscourseteacher'] = 'DisKurs als Lehrender ansehen';
$string['discourse:viewdiscoursestudent'] = 'DisKurs als Teilnehmer ansehen';
$string['discourse:editsubmission'] = 'Gruppentext einreichen oder bearbeiten';
$string['discourse:editphase'] = 'Phaseninformationen bearbeiten';
$string['discourse:switchphase'] = 'Diskurs-Phase umschalten';
$string['discourse:viewallgroups'] = 'Alle Gruppen sehen';
$string['discourse:viewgroupparticipants'] = 'Alle Gruppenteilnehmer einsehen';

// privacy
$string['privacy:metadata:discourse_participants'] = 'Enthält die persönlichen Daten aller DisKurs Teilnehmenden.';
$string['privacy:metadata:discourse_submissions'] = 'Enthält alle Daten zu DisKurs Einreichungen.';
$string['privacy:metadata:discourse_participants:discourse'] = 'ID des DisKurses des Teilnehmers';
$string['privacy:metadata:discourse_submissions:discourse'] = 'ID des DisKurses der Einreichung';
$string['privacy:metadata:core_message'] = 'Das DisKurs Plugin sendet Nachrichten an Benutzer und speichert deren Inhalte in der Datenbank.';