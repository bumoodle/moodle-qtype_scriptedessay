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
 * Defines the editing form for the essay question type.
 *
 * @package    qtype
 * @subpackage essay
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/essay/edit_essay_form.php');
require_once($CFG->dirroot.'/question/type/scripted/lib.php');

/**
 * Scripted Essay editing form.
 *
 * @copyright  2012 Kyle J. Temkin, Binghamton University <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_scriptedessay_edit_form extends qtype_essay_edit_form 
{

    /**
     * Provides the editing form for a Scripted Essay question.
     */
    protected function definition_inner($mform) 
    {
        //add the essay question parameters
        parent::definition_inner($mform);
        
        //add an initialization code editor
        $mform->addElement('scripteditor', 'init_code', get_string('initscript', 'qtype_scripted'));
    }

    /**
     * Process the question data, to ready it for editing.
     */
    protected function data_preprocessing($question)
    {
        //allow the core essay routines to preprocess the question object
        $question = parent::data_preprocessing($question);

        //and then pass in the question's initialization code
        $question->init_code = $question->options->init_code;

        //return the modified question
        return $question;
    }

    /**
     * Indicates the question type to which this form belongs.
     */
    public function qtype() 
    {
        return 'scriptedessay';
    }
}
