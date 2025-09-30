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

namespace block_vexturintroduction;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/grade/querylib.php');
require_once($CFG->dirroot . '/lib/grade/grade_item.php');
require_once($CFG->dirroot . '/lib/grade/grade_grade.php');
require_once($CFG->dirroot . '/lib/gradelib.php');
require_once($CFG->dirroot . '/message/classes/api.php');

/**
 * Class for block data.
 *
 * @package    block_vexturintroduction
 * @copyright  UAB "Vextur" <info@vextur.com>
 * @author     2023, Beata Gancevska <beata@vextur.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class blockdata
{
    /**
     * Get user greeting.
     *
     * @return string
     */
    private function get_user_greeting() {
        global $USER;

        return get_string('hellouser', 'block_vexturintroduction', $USER->firstname);
    }

    /**
     * Get block background image url.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    public function get_background_image_url() {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        $url = '';
        $context = \context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_vexturintroduction', 'backgroundimage');
        foreach ($files as $f) {
            if ($f->is_valid_image()) {
                $url = \moodle_url::make_pluginfile_url(
                    $f->get_contextid(),
                    $f->get_component(),
                    $f->get_filearea(),
                    $f->get_itemid(),
                    $f->get_filepath(),
                    $f->get_filename()
                );
                $url = $url->out();
            }
        }

        return $url;
    }

    /**
     * Get custom block text.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function get_custom_block_text() {
        return get_config('block_vexturintroduction', 'customtext');
    }

    /**
     * Get user enrolled courses count.
     *
     * @return int number of courses where user is enrolled.
     */
    private function get_user_courses_count() {
        $userenrolledcourses = enrol_get_my_courses();
        $coursescount = count($userenrolledcourses);

        return $coursescount;
    }

    /**
     * Get user completed courses.
     *
     * @return int number of user completed courses.
     */
    private function get_user_completed_courses() {
        global $DB, $USER;

        $completedcourses = $DB->count_records_sql("SELECT COUNT(*)
                                                          FROM {course_completions}
                                                         WHERE userid = $USER->id
                                                           AND timecompleted IS NOT NULL");

        return $completedcourses;
    }

    /**
     * Get user courses average grade.
     *
     * @return int|float|false courses average grade, false otherwise.
     */
    private function get_user_courses_average_grade() {
        global $USER;

        // Get all user enrolled courses.
        $userenrolledcourses = enrol_get_my_courses();
        $coursesids = [];
        $sumgrades = 0;
        $countgrades = 0;
        $averagegrade = false;

        if ($userenrolledcourses) {
            // Get courses ids where user is enrolled.
            foreach ($userenrolledcourses as $userenrolledcourse) {
                $coursesids[] = $userenrolledcourse->id;
            }

            // If have enrolled courses.
            if ($coursesids) {
                // Get grades for enrolled courses.
                $gradeimtems = grade_get_course_grade($USER->id, $coursesids);
                foreach ($gradeimtems as $gradeimtem) {
                    // Course final grade.
                    $grade = $gradeimtem->grade;

                    // Count sum only if number.
                    if (is_numeric($grade)) {
                        $sumgrades += $grade;
                        $countgrades++;
                    }
                }

                // If exists numeric grade then count average.
                if ($countgrades > 0) {
                    $averagegrade = $sumgrades / $countgrades;
                    $averagegrade = round($averagegrade, 2);
                }
            }
        }

        return $averagegrade;
    }

    /**
     * Get unread conversations count.
     *
     * @return int unread conversations count.
     */
    private function get_unread_conversations_count() {
        global $USER;

        return \core_message\api::count_unread_conversations($USER);
    }

    /**
     * Get Quiz and Assign activities count where deadline in 7 days.
     *
     * @return int Quiz and Assign activities count.
     */
    private function get_activities_count() {
        $now = time();
        $timestampaftersevendays = strtotime('+7 days', $now);
        $activitiescount = 0;

        // Get all user enrolled courses.
        $userenrolledcourses = enrol_get_my_courses();
        foreach ($userenrolledcourses as $userenrolledcourse) {
            // Get info about modules in course for user.
            $modinfo = get_fast_modinfo($userenrolledcourse->id);

            // Get course modules.
            $cms = $modinfo->get_cms();
            foreach ($cms as $cm) {
                if (
                    ($cm->uservisible && $cm->modname === 'assign' &&
                        isset($cm->customdata['duedate']) && $cm->customdata['duedate'] > $now &&
                            $cm->customdata['duedate'] <= $timestampaftersevendays) ||
                    ($cm->uservisible && $cm->modname === 'quiz' &&
                        isset($cm->customdata['timeclose']) && $cm->customdata['timeclose'] > $now &&
                            $cm->customdata['timeclose'] <= $timestampaftersevendays)
                ) {
                    $activitiescount++;
                }
            }
        }

        return $activitiescount;
    }

    /**
     * Function for get showcompletedcourses config.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function show_completed_courses() {
        return get_config('block_vexturintroduction', 'showcompletedcourses');
    }

    /**
     * Function for get showunreadmessages config.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function show_unread_messages() {
        return get_config('block_vexturintroduction', 'showunreadmessages');
    }

    /**
     * Function for get showactivities config.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function show_activities() {
        return get_config('block_vexturintroduction', 'showactivities');
    }

    /**
     * Function for get showavggrade config.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function show_avggrade() {
        return get_config('block_vexturintroduction', 'showavggrade');
    }

    /**
     * Function for check if no icons design enabled.
     *
     * @return bool
     */
    public function is_enabled_no_icons_design() {
        $designtype = get_config('block_vexturintroduction', 'blockdesign');
        if ($designtype === 'noicons') {
            return true;
        }

        return false;
    }

    /**
     * Function for check if with icons design enabled.
     *
     * @return bool
     */
    private function is_enabled_with_icons_design() {
        $designtype = get_config('block_vexturintroduction', 'blockdesign');
        if ($designtype === 'withicons') {
            return true;
        }

        return false;
    }

    /**
     * Function for get block background colour.
     *
     * @return bool
     */
    private function get_block_background_colour() {
        return get_config('block_vexturintroduction', 'backgroundcolour');
    }

    /**
     * Function for get main text colour.
     *
     * @return bool
     */
    private function get_block_main_text_colour() {
        return get_config('block_vexturintroduction', 'maintxtcolour');
    }

    /**
     * Function for get additional text colour.
     *
     * @return bool
     */
    private function get_block_additional_text_colour() {
        return get_config('block_vexturintroduction', 'additionaltxtcolour');
    }

    /**
     * Function for get icons colour.
     *
     * @return bool
     */
    private function get_block_icons_colour() {
        return get_config('block_vexturintroduction', 'iconscolour');
    }

    /**
     * Function for get my courses url.
     *
     * @return \moodle_url
     */
    private function get_my_courses_url() {
        global $CFG;

        $mycoursesurl = new \moodle_url($CFG->wwwroot . '/my/courses.php');

        return $mycoursesurl;
    }

    /**
     * Function for get messages url.
     *
     * @return \moodle_url
     */
    private function get_messages_url() {
        global $CFG;

        $messagesulr = new \moodle_url($CFG->wwwroot . '/message/index.php');

        return $messagesulr;
    }

    /**
     * Function for get grades url.
     *
     * @return \moodle_url
     */
    private function get_grades_url() {
        global $CFG, $USER;

        $gradesulr = new \moodle_url($CFG->wwwroot . '/grade/report/overview/index.php', ['userid' => $USER->id]);

        return $gradesulr;
    }

    /**
     * Function for checking if show column names as links enabled.
     *
     * @return mixed hash-like object or single value, return false no config found
     */
    private function show_column_names_as_links() {
        return get_config('block_vexturintroduction', 'showlinks');
    }

    /**
     * Function for checking user is editing.
     *
     * @return bool editmode
     */
    private function get_editmode() {
        global $PAGE;

        $editmode = false;
        if ($PAGE->user_is_editing()) {
            $editmode = true;
        }

        return $editmode;
    }

    /**
     * Function for output block data.
     *
     * @return object
     */
    public function get_block_data() {
        $count = $this->show_completed_courses() + $this->show_unread_messages() + $this->show_activities() +
                (($this->get_user_courses_average_grade() && $this->show_avggrade()) ? 1 : 0) + 1;
        return (object)[
            'greeting' => $this->get_user_greeting(),
            'backgroundimageurl' => $this->get_background_image_url(),
            'customtext' => $this->get_custom_block_text(),
            'coursescount' => $this->get_user_courses_count(),
            'averagegrade' => $this->get_user_courses_average_grade(),
            'unreadconversations' => $this->get_unread_conversations_count(),
            'activitiescount' => $this->get_activities_count(),
            'completedcourses' => $this->get_user_completed_courses(),
            'showcompletedcourses' => $this->show_completed_courses(),
            'showunreadmessages' => $this->show_unread_messages(),
            'showactivities' => $this->show_activities(),
            'showavggrade' => $this->show_avggrade(),
            'noiconsdesign' => $this->is_enabled_no_icons_design(),
            'withiconsdesign' => $this->is_enabled_with_icons_design(),
            'bgcolour' => $this->get_block_background_colour(),
            'maintxtcolour' => $this->get_block_main_text_colour(),
            'additionaltxtcolour' => $this->get_block_additional_text_colour(),
            'iconscolour' => $this->get_block_icons_colour(),
            'mycoursesurl' => $this->get_my_courses_url(),
            'messagesurl' => $this->get_messages_url(),
            'gradesurl' => $this->get_grades_url(),
            'showlinks' => $this->show_column_names_as_links(),
            'editmode' => $this->get_editmode(),
            'count' => $count,
            'smcol' => $count < 2 ? 1 : 2,
            'mdcol' => $count % 3,
        ];
    }
}
