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
 * Essay question renderer class.
 *
 * @package    qtype
 * @subpackage essay
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/essay/renderer.php');

/**
 * Generates the output for essay questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_scriptedessay_renderer extends qtype_essay_renderer {

    /**
     * Displays any attached files when the question is in read-only mode.
     * @param question_attempt $qa the question attempt to display.
     * @param question_display_options $options controls what should and should
     *      not be displayed. Used to get the context.
     */
    public function files_read_only(question_attempt $qa, question_display_options $options) {
        $files = $qa->get_last_qt_files('attachments', $options->context->id);

        $output = array();

        print_object($files);

        foreach ($files as $file) {

            switch($file->get_mimetype()) {

                case 'image/jpeg':
                    $output[] = html_writer::tag('p', html_writer::empty_tag('img', array('src' => $qa->get_response_file_url($file))));
                    break;

                default:
                    $output[] = html_writer::tag('p', html_writer::link($qa->get_response_file_url($file),
                        $this->output->pix_icon(file_file_icon($file), get_mimetype_description($file),
                        'moodle', array('class' => 'icon')) . ' ' . s($file->get_filename())));
                    break;


            }
        }
        return implode($output);
    }


}


