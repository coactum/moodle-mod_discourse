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
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'DisKurs';

$string['modulename'] = 'DisKurs';
$string['modulename_help'] = 'Die Aktivität DisKurs bietet die Möglichkeit, in einem mehrstufigen Verfahren Gruppendiskussionen durchzuführen.

Basierend auf dem Konzept der Pyramidendiskussion verfassen die Teilnehmenden des DisKurses in einer ersten Phase zunächst einen eigenen Text zum diskutierten Thema. In den nächsten beiden Phasen kombinieren sie ihre eigenen Positionen dann mit denen anderer Teilnehmender und erarbeiten so zusammen differenziertere Texte unter Einbezug anderer Standpunkte. In der letzten Phase entwickeln schließlich alle Teilnehmenden gemeinsam auf Basis ihrer bisherigen Ergebnisse eine finale Position zum diskutierten Thema.

Auf diese Weise ermöglicht der DisKurs den Teilnehmenden, gemeinsam Argumente und Perspektiven zu einem Thema zu erarbeiten und diese gegen- und miteinander abzuwägen. Dies erlaubt es den Teilnehmenden, in einem reflexiven Austausch differenzierte Positionen zu einem komplexen Thema zu entwickeln und macht dabei durch Anzeigen der bisherigen Zwischenpositionen zugleich den Diskussionsfortschritt sichtbar.

Auf der Übersichtsseite können Lehrende …

* Alle Phasen und die bei der Erstellung der Aktivität angegebenen Phasendeadlines und Phasenhinweise ansehen
* Eine Übersicht über alle DisKurs-Gruppen und deren Abgabestatus ansehen und die Gruppenseite jeder dieser Gruppen öffnen
* Die aktuelle Phase wechseln (die Phasen können auch automatisch von Moodle zur festgelegten Frist gewechselt werden)

Teilnehmende können dort …

* Ebenfalls alle Phasen, Phasendeadlines und Phasenhinweise einsehen
* Außerdem ihre eigenen Gruppen in den verschiedenen Phasen ansehen und deren Gruppenseiten öffnen

Auf der Gruppenseite können Lehrende …

* Die Mitglieder, den Abgabestatus und (falls vorhanden) die Einreichung der gewählten Gruppe ansehen
* Den Hinweis für die Phase der gewählten Gruppe ansehen
* Die Positionen jeder in dieser Gruppe enthaltenen Gruppe aus der vorherigen Phase ansehen

Teilnehmende können dort zusätzlich eine Einreichung für die Gruppe abgeben falls die Phase der Gruppe der aktuellen Phase des DisKurses entspricht (die Positionen der Vorgruppen sind dann im Editor bereits vorausgefüllt)';
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
$string['errinvalidphasedeadline'] = 'Die Phasen-Deadlines sind ungültig.';

$string['modulenameplural'] = 'DisKurse';
$string['nonewmodules'] = 'Keine neuen DisKurse';

$string['deletealluserdata'] = 'Alle Benutzerdaten (Teilnehmer, Einreichungen, Gruppen und Groupings) löschen';
$string['userdatadeleted'] = 'Benutzerdaten gelöscht';

$string['groupingdescription'] = 'Gruppierung für die DisKurs-Aktivität {$a}';
$string['phaseone'] = 'Einzelphase';
$string['phasetwo'] = '1. Gruppenphase';
$string['phasethree'] = '2. Gruppenphase';
$string['phasefour'] = 'Gemeinschaftsphase';
$string['groupfor'] = 'Gruppe für {$a}';
$string['group'] = 'Gruppe';

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
$string['groupmodevisiblegroups'] = 'Der Gruppenmodus "Sichtbare Gruppen" ist aktiviert. Dadurch können alle Teilnehmenden alle Gruppen und deren Einreichungen ansehen.';

$string['view'] = 'Übersicht';

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
$string['deletedgroup'] = 'Gelöschte Gruppe';
$string['phaseactive'] = 'Phase aktiv';
$string['phaseclosed'] = 'Phase abgeschlossen';

$string['discourse:addinstance'] = 'Neuen DisKurs hinzufügen';
$string['discourse:potentialparticipant'] = 'DisKurs als Teilnehmer beitreten';
$string['discourse:editsubmission'] = 'Gruppentext einreichen oder bearbeiten';
$string['discourse:editphase'] = 'Phaseninformationen bearbeiten';
$string['discourse:switchphase'] = 'DisKurs-Phase umschalten';
$string['discourse:viewallgroups'] = 'Alle Gruppen sehen';
$string['discourse:viewgroupparticipants'] = 'Alle Gruppenteilnehmer einsehen';

$string['groupinvalid'] = 'Gruppe nicht vorhanden';
$string['useridinvalid'] = 'Benutzer-ID ungültig';
$string['nogroupmember'] = 'Nicht möglich da kein Gruppenmitglied';
$string['submissionfaileddoubled'] = 'Einreichung fehlgeschlagen. Ein weiteres Gruppenmitglied hat bereits kürzlich eine Einreichung abgegeben.';
$string['groupingmaybedeleted'] = 'Die zum DisKurs gehörende Gruppierung wurde gelöscht oder ist ungültig.';
$string['alreadyparticipants'] = 'Bereits Teilnehmer vorhanden. Gruppen- und Teilnehmererstellung abgebrochen.';
$string['alreadygrouping'] = 'Es ist bereits eine Gruppierung vorhanden. Gruppen- und Teilnehmererstellung abgebrochen.';

$string['task_switchphases'] = 'Automatischer Phasenwechsel';

$string['privacy:metadata:discourse_participants'] = 'Enthält die Gruppen aller DisKurs Teilnehmenden.';
$string['privacy:metadata:discourse_submissions'] = 'Enthält Informationen zu allen DisKurs Einreichungen.';
$string['privacy:metadata:discourse_participants:userid'] = 'Benutzer-ID der oder des Teilnehmenden';
$string['privacy:metadata:discourse_participants:discourse'] = 'ID des DisKurses der oder des Teilnehmenden';
$string['privacy:metadata:discourse_participants:groupids'] = 'IDs der DisKurs-Gruppen der oder des Teilnehmenden';
$string['privacy:metadata:discourse_submissions:discourse'] = 'ID des DisKurses, in dem die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:groupid'] = 'ID der Gruppe, von der die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:submission'] = 'Inhalt der Einreichung';
$string['privacy:metadata:discourse_submissions:currentversion'] = 'Aktuelle Version der Einreichung';
$string['privacy:metadata:discourse_submissions:format'] = 'Format der Einreichung';
$string['privacy:metadata:discourse_submissions:timecreated'] = 'Zeitpunkt, an dem die Einreichung abgegeben wurde';
$string['privacy:metadata:discourse_submissions:timemodified'] = 'Zeitpunkt der letzten Überarbeitung der Einreichung';
$string['privacy:metadata:core_group'] = 'Das DisKurs-Plugin erstellt und nutzt Gruppen und Gruppierungen';
