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
 * Settings for Vextur introduction block.
 *
 * @package    block_vexturintroduction
 * @copyright  UAB "Vextur" <info@vextur.com>
 * @author     2023, Beata Gancevska <beata@vextur.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Block general settings.
    $setting = new admin_setting_heading(
        'block_vexturintroduction/generalsettingsheading',
        new lang_string('generalsettings', 'block_vexturintroduction'),
        '<br>'
    );
    $settings->add($setting);

    // Block design.
    $setting = new admin_setting_configselect(
        'block_vexturintroduction/blockdesign',
        get_string('blockdesign', 'block_vexturintroduction'),
        '',
        'withicons',
        [
            'noicons' => get_string('designnoicons', 'block_vexturintroduction'),
            'withicons' => get_string('designwithicons', 'block_vexturintroduction'),
        ]
    );
    $settings->add($setting);

    // Show completed courses.
    $setting = new admin_setting_configcheckbox(
        'block_vexturintroduction/showcompletedcourses',
        get_string('showcompletedcourses', 'block_vexturintroduction'),
        '',
        0
    );
    $settings->add($setting);

    // Show unread messages.
    $setting = new admin_setting_configcheckbox(
        'block_vexturintroduction/showunreadmessages',
        get_string('showunreadmessages', 'block_vexturintroduction'),
        '',
        1
    );
    $settings->add($setting);

    // Show activities.
    $setting = new admin_setting_configcheckbox(
        'block_vexturintroduction/showactivities',
        get_string('showactivities', 'block_vexturintroduction'),
        '',
        1
    );
    $settings->add($setting);

    // Show avg grade.
    $setting = new admin_setting_configcheckbox(
        'block_vexturintroduction/showavggrade',
        get_string('showavggrade', 'block_vexturintroduction'),
        '',
        1
    );
    $settings->add($setting);

    // Show column names as links.
    $setting = new admin_setting_configcheckbox(
        'block_vexturintroduction/showlinks',
        get_string('showlinks', 'block_vexturintroduction'),
        '',
        0
    );
    $settings->add($setting);

    // Design without icons settings.
    $setting = new admin_setting_heading(
        'block_vexturintroduction/designwithouticonsheading',
        new lang_string('designwithouticons', 'block_vexturintroduction'),
        '<br>'
    );
    $settings->add($setting);

    // Background image.
    $setting = new admin_setting_configstoredfile(
        'block_vexturintroduction/backgroundimage',
        new lang_string('backgroundimage', 'block_vexturintroduction'),
        '',
        'backgroundimage',
        0,
        ['accepted_types' => ['image']]
    );
    $settings->add($setting);

    // Text to show in block.
    $setting = new admin_setting_confightmleditor(
        'block_vexturintroduction/customtext',
        new lang_string('customtext', 'block_vexturintroduction'),
        '',
        '',
        PARAM_RAW
    );
    $settings->add($setting);

    // Design with icons settings.
    $setting = new admin_setting_heading(
        'block_vexturintroduction/designwithiconsheading',
        new lang_string('designwithiconssettings', 'block_vexturintroduction'),
        '<br>'
    );
    $settings->add($setting);

    // Block background colour.
    $setting = new admin_setting_configcolourpicker(
        'block_vexturintroduction/backgroundcolour',
        new lang_string('backgroundcolour', 'block_vexturintroduction'),
        '',
        '#fff'
    );
    $settings->add($setting);

    // Main text colour.
    $setting = new admin_setting_configcolourpicker(
        'block_vexturintroduction/maintxtcolour',
        new lang_string('maintxtcolour', 'block_vexturintroduction'),
        '',
        '#1d2125'
    );
    $settings->add($setting);

    // Additional text colour.
    $setting = new admin_setting_configcolourpicker(
        'block_vexturintroduction/additionaltxtcolour',
        new lang_string('additionaltxtcolour', 'block_vexturintroduction'),
        '',
        '#1d2125'
    );
    $settings->add($setting);

    // Icons colour.
    $setting = new admin_setting_configcolourpicker(
        'block_vexturintroduction/iconscolour',
        new lang_string('iconscolour', 'block_vexturintroduction'),
        '',
        '#0f6cbf'
    );
    $settings->add($setting);
}
