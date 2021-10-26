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
$string['modulename_help'] = 'Die Aktivität DisKurs bietet die Möglichkeit, in einem mehrschrittigen Verfahren Gruppendiskussionen durchzuführen.

Basierend auf dem Konzept der Pyramidendiskussion verfassen die Teilnehmenden des Kurses in einer ersten Phase zunächst einen eigenen Text zum diskutierten Thema. In den nächsten beiden Phasen kombinieren sie ihre eigene Position dann mit denen anderer Teilnehmender und erarbeiten so differenziertere Texte unter Einbezug anderer Standpunkte. In der letzten Phase entwickeln schließlich alle Kursteilnehmenden gemeinsam auf Basis ihrer bisherigen Ergebnisse eine finale Position zum diskutierten Thema.

Auf diese Weise ermöglicht diese Aktivität den Kursteilnehmenden, gemeinsam Argumente und Perspektiven zu einem Thema zu erarbeiten und diese gegen- und miteinander abzuwägen, um in einem reflexiven Austausch differenzierte Positionen zu einem komplexen Thema zu entwickeln.';
$string['modulename_link'] = 'mod/discourse/view';
$string['pluginadministration'] = 'Administration des DisKurses';
$string['phasecompletion'] = 'Phasen-Abschluss';
$string['usedeadlines'] = 'Deadlines für Phasen nutzen';
$string['modeautoswitch'] = 'Modus "Automatischer Phasenwechsel" aktivieren';
$string['autoswitch'] = 'Die Phasen werden automatisch zu den angegebenen Zeitpunkten gewechselt';
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
$string['resetting_data'] = 'TeilnehmerInnen und Einreichungen gelöscht';

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
$string['openhint'] = 'Hinweis für {$a} öffnen oder verstecken';
$string['sheduledend'] = 'Geplantes Ende';
$string['toggleallgroups'] = 'Alle Gruppen der {$a} umschalten';
$string['opengroup'] = '{$a} maximieren';
$string['closegroup'] = '{$a} minimieren';
$string['groupparticipants'] = 'TeilnehmerInnen';
$string['submission'] = 'Einreichung';
$string['nosubmission'] = 'Noch keine Einreichung';
$string['submissionreceived'] = 'Einreichung abgegeben';
$string['nogroups'] = 'Keine Gruppen vorhanden';
$string['noautoswitch'] = 'Der automatische Phasenwechsel ist deaktiviert. Die Phasen müssen deshalb manuell gewechselt werden.';
$string['shouldswitchphaseto'] = 'Der automatische Phasenwechsel ist deaktiviert. Die nächste Phase sollte aktiviert werden.';
$string['phaseswitched'] = 'Phase gewechselt.';

// view.php
$string['view'] = 'Übersicht';

// groupview.php
$string['groupview'] = 'Gruppenansicht';
$string['phasehint'] = 'Phasenhinweis';
$string['submissionstate'] = 'Status';
$string['submitted'] = 'Abgegeben am';
$string['notsubmitted'] = 'Noch nicht abgegeben';
$string['updated'] = 'Aktualisiert am';
$string['submitsubmission'] = 'Einreichung abgeben';
$string['editsubmission'] = 'Einreichung bearbeiten';
$string['currentversion'] = 'Aktuelle Version';
$string['positionsfromlastphase'] = 'Positionen der letzten Phase';
$string['currentsubmission'] = 'Aktuelle Einreichung';
$string['submissioncontent'] = 'Inhalt der Einreichung';
$string['errfilloutfield'] = 'Bitte Feld ausfüllen';
$string['backtooverview'] = 'Zurück zur Übersicht';

// capabilities
$string['discourse:addinstance'] = 'Neuen DisKurs hinzufügen';
$string['discourse:potentialparticipant'] = 'DisKurs als Teilnehmer beitreten';
$string['discourse:editsubmission'] = 'Gruppentext einreichen oder bearbeiten';
$string['discourse:editphase'] = 'Phaseninformationen bearbeiten';
$string['discourse:switchphase'] = 'Diskurs-Phase umschalten';
$string['discourse:viewallgroups'] = 'Alle Gruppen sehen';
$string['discourse:viewgroupparticipants'] = 'Alle Gruppenteilnehmer einsehen';

// errors
$string['groupinvalid'] = 'Gruppe nicht vorhanden';
$string['useridinvalid'] = 'Benutzer-ID ungültig';
$string['nogroupmember'] = 'Nicht möglich da kein Gruppenmitglied';
$string['submissionfaileddoubled'] = 'Einreichung fehlgeschlagen. Ein weiteres Gruppenmitglied hat bereits kürzlich eine Einreichung abgegeben.';
$string['groupingmaybedeleted'] = 'Die zum Diskurs gehörende Gruppierung wurde gelöscht oder ist ungültig. Es werden sämtliche Kursgruppen (auch aus anderen Diskursen oder Aktivitäten) angezeigt.';

// task
$string['task_switchphases'] = 'Automatischer Phasenwechsel';

// privacy
$string['privacy:metadata:discourse_participants'] = 'Enthält die Gruppen aller DisKurs Teilnehmenden.';
$string['privacy:metadata:discourse_submissions'] = 'Enthält Informationen zu allen DisKurs Einreichungen.';
$string['privacy:metadata:discourse_participants:userid'] = 'Benutzer-ID der oder des Teilnehmenden';
$string['privacy:metadata:discourse_participants:discourse'] = 'ID des DisKurses der oder des Teilnehmenden';
$string['privacy:metadata:discourse_participants:groupids'] = 'IDs der DisKurs-Gruppen der oder des Teilnehmenden';
$string['privacy:metadata:discourse_submissions:discourse'] = 'ID des DisKurses in dem die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:groupid'] = 'ID der Gruppe von der die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:submission'] = 'Inhalt der Einreichung';
$string['privacy:metadata:discourse_submissions:currentversion'] = 'Aktuelle Version der Einreichung';
$string['privacy:metadata:discourse_submissions:format'] = 'Format der Einreichung';
$string['privacy:metadata:discourse_submissions:timecreated'] = 'Zeitpunkt an dem die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:timemodified'] = 'Zeitpunkt der letzten Überarbeitung der Einreichung';
