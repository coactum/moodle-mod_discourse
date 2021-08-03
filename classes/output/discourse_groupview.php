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
 * Class containing data for group view in discourse activity
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_discourse\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Class containing data for discourse_view
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class discourse_groupview implements renderable, templatable {

    /** @var int */
    protected $cmid;
    /** @var array */
    protected $group;
    /** @var string */
    protected $form;
    /** @var bool */
    protected $caneditsubmission;
    /** @var bool */
    protected $canviewgroupparticipants;
    /**
     * Construct this renderable.
     * @param int $cmid The course module id
     * @param array $group The group that should be viewed
     * @param string $form The form for submitting text
     * @param bool $caneditsubmission Boolean if user can edit the submission
     * @param bool $canviewgroupparticipants Boolean if user can see group participants
     */
    public function __construct($cmid, $group, $form, $caneditsubmission, $canviewgroupparticipants) {
        $this->cmid = $cmid;
        $this->group = $group;
        $this->form = $form;
        $this->caneditsubmission = $caneditsubmission;
        $this->canviewgroupparticipants = $canviewgroupparticipants;
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
        $data->group = $this->group;
        $data->form = $this->form;
        $data->caneditsubmission = $this->caneditsubmission;
        $data->canviewgroupparticipants = $this->canviewgroupparticipants;
        return $data;
    }
}