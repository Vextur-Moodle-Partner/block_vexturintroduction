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
 * Contains the class for the Vextur introduction block.
 *
 * @package    block_vexturintroduction
 * @copyright  UAB "Vextur" <info@vextur.com>
 * @author     2023, Beata Gancevska <beata@vextur.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/classes/blockdata.php');

/**
 * Class for the Vextur introduction block.
 *
 * @package    block_vexturintroduction
 * @copyright  UAB "Vextur" <info@vextur.com>
 * @author     2023, Beata Gancevska <beata@vextur.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_vexturintroduction extends block_base
{
    /**
     * Init method, every subclass will have its own
     */
    public function init() {
        $this->title = '';
    }

    /**
     * Returns the contents.
     *
     * @return stdClass | object contents of block
     */
    public function get_content() {
        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();

        if (isloggedin()) {
            $renderable = new block_vexturintroduction\output\main();
            $renderer = $this->page->get_renderer('block_vexturintroduction');
            $this->content->text = $renderer->render($renderable);

            $blockdata = new \block_vexturintroduction\blockdata();
            $noiconsdesign = $blockdata->is_enabled_no_icons_design();

            if ($noiconsdesign) {
                $backgroundimageurl = $blockdata->get_background_image_url();
                if ($backgroundimageurl) {
                    $this->page->requires->js_call_amd('block_vexturintroduction/main', 'init', [
                            'imageurl' => $backgroundimageurl,
                    ]);
                }
            }
        } else {
            $this->content->text = '';
        }

        $this->content->footer = '';

        return $this->content;
    }

    /**
     * All multiple instances of this block
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Controls global configurability of block.
     *
     * @return bool
     */
    public function has_config(): bool {
        return true;
    }
}
