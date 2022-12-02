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
 * Class containing data for discourse main page
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_discourse\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Class containing data for discourse_view
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class discourse_view implements renderable, templatable {

    /** @var int */
    protected $cmid;
    /** @var array */
    protected $groups;
    /** @var int */
    protected $autoswitch;
    /** @var bool */
    protected $activephaseone;
    /** @var bool */
    protected $activephasetwo;
    /** @var bool */
    protected $activephasethree;
    /** @var bool */
    protected $activephasefour;
    /** @var string */
    protected $hintphaseone;
    /** @var string */
    protected $hintphasetwo;
    /** @var string */
    protected $hintphasethree;
    /** @var string */
    protected $hintphasefour;
    /** @var bool */
    protected $hintphaseoneshortened;
    /** @var bool */
    protected $hintphasetwoshortened;
    /** @var bool */
    protected $hintphasethreeshortened;
    /** @var bool */
    protected $hintphasefourshortened;
    /** @var int */
    protected $deadlinephaseone;
    /** @var int */
    protected $deadlinephasetwo;
    /** @var int */
    protected $deadlinephasethree;
    /** @var int */
    protected $deadlinephasefour;
    /** @var bool */
    protected $caneditphase;
    /** @var bool */
    protected $canswitchphase;
    /** @var bool */
    protected $canviewallgroups;
    /** @var bool */
    protected $canviewgroupparticipants;
    /** @var int */
    protected $shouldswitchphase;
    /** @var int */
    protected $userid;
    /** @var int */
    protected $sesskey;
    /** @var bool */
    protected $phasehints;
    /**
     * Construct this renderable.
     * @param int $cmid The course module id
     * @param array $groups The groups for the discourse
     * @param int $autoswitch If phases should switch automatically
     * @param bool $activephaseone If phase one is active
     * @param bool $activephasetwo If phase two is active
     * @param bool $activephasethree If phase three is active
     * @param bool $activephasefour If phase four is active
     * @param string $hintphaseone Hint for phase one
     * @param string $hintphasetwo Hint for phase two
     * @param string $hintphasethree Hint for phase three
     * @param string $hintphasefour Hint for phase four
     * @param bool $hintphaseoneshortened If hint for phase one is shortened
     * @param bool $hintphasetwoshortened If hint for phase one is shortened
     * @param bool $hintphasethreeshortened If hint for phase one is shortened
     * @param bool $hintphasefourshortened If hint for phase one is shortened
     * @param int $deadlinephaseone Deadline for phase one
     * @param int $deadlinephasetwo Deadline for phase two
     * @param int $deadlinephasethree Deadline for phase three
     * @param int $deadlinephasefour Deadline for phase four
     * @param bool $caneditphase If user can edit phase information
     * @param bool $canswitchphase If user can switch phases
     * @param bool $canviewallgroups If user can view all groups
     * @param bool $canviewgroupparticipants If user can view all groups
     * @param int $shouldswitchphase If user should switch phase manually
     * @param int $userid ID of current user
     * @param int $sesskey Session key for changing phases
     * @param bool $phasehints If any phase hints are set.
     */
    public function __construct($cmid, $groups, $autoswitch, $activephaseone, $activephasetwo, $activephasethree, $activephasefour,
        $hintphaseone, $hintphasetwo, $hintphasethree, $hintphasefour, $hintphaseoneshortened, $hintphasetwoshortened, $hintphasethreeshortened,
        $hintphasefourshortened, $deadlinephaseone, $deadlinephasetwo, $deadlinephasethree, $deadlinephasefour, $caneditphase, $canswitchphase,
        $canviewallgroups, $canviewgroupparticipants, $shouldswitchphase, $userid, $sesskey, $phasehints) {

        $this->cmid = $cmid;
        $this->groups = $groups;
        $this->autoswitch = $autoswitch;
        $this->activephaseone = $activephaseone;
        $this->activephasetwo = $activephasetwo;
        $this->activephasethree = $activephasethree;
        $this->activephasefour = $activephasefour;
        $this->hintphaseone = $hintphaseone;
        $this->hintphasetwo = $hintphasetwo;
        $this->hintphasethree = $hintphasethree;
        $this->hintphasefour = $hintphasefour;
        $this->hintphaseoneshortened = $hintphaseoneshortened;
        $this->hintphasetwoshortened = $hintphasetwoshortened;
        $this->hintphasethreeshortened = $hintphasethreeshortened;
        $this->hintphasefourshortened = $hintphasefourshortened;
        $this->deadlinephaseone = $deadlinephaseone;
        $this->deadlinephasetwo = $deadlinephasetwo;
        $this->deadlinephasethree = $deadlinephasethree;
        $this->deadlinephasefour = $deadlinephasefour;
        $this->caneditphase = $caneditphase;
        $this->canswitchphase = $canswitchphase;
        $this->canviewallgroups = $canviewallgroups;
        $this->canviewgroupparticipants = $canviewgroupparticipants;
        $this->shouldswitchphase = $shouldswitchphase;
        $this->userid = $userid;
        $this->sesskey = $sesskey;
        $this->phasehints = $phasehints;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Renderer base.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->cmid = $this->cmid;
        $data->groups = $this->groups;
        $data->autoswitch = $this->autoswitch;
        $data->activephaseone = $this->activephaseone;
        $data->activephasetwo = $this->activephasetwo;
        $data->activephasethree = $this->activephasethree;
        $data->activephasefour = $this->activephasefour;
        $data->hintphaseone = $this->hintphaseone;
        $data->hintphasetwo = $this->hintphasetwo;
        $data->hintphasethree = $this->hintphasethree;
        $data->hintphasefour = $this->hintphasefour;
        $data->hintphaseoneshortened = $this->hintphaseoneshortened;
        $data->hintphasetwoshortened = $this->hintphasetwoshortened;
        $data->hintphasethreeshortened = $this->hintphasethreeshortened;
        $data->hintphasefourshortened = $this->hintphasefourshortened;
        $data->deadlinephaseone = $this->deadlinephaseone;
        $data->deadlinephasetwo = $this->deadlinephasetwo;
        $data->deadlinephasethree = $this->deadlinephasethree;
        $data->deadlinephasefour = $this->deadlinephasefour;
        $data->caneditphase = $this->caneditphase;
        $data->canswitchphase = $this->canswitchphase;
        $data->canviewallgroups = $this->canviewallgroups;
        $data->canviewgroupparticipants = $this->canviewgroupparticipants;
        $data->shouldswitchphase = $this->shouldswitchphase;
        $data->userid = $this->userid;
        $data->sesskey = $this->sesskey;
        $data->phasehints = $this->phasehints;
        return $data;
    }
}
