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
 * Question type class for the scriptedessay question type.
 *
 * @package    qtype
 * @subpackage scriptedessay
 * @copyright  2012 Kyle J. Temkin, Binghamton University <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


require_once($CFG->dirroot.'/question/type/essay/questiontype.php');

/**
 * An extension to the Essay question type which supports randomized essay prompts.
 *
 * @copyright  2012 Kyle J. Temkin, Binghamton University <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_scriptedessay extends qtype_essay 
{
    /**
     * Retrieves the extra question options from the database.
     */
    public function get_question_options($question)
    {
        //use the database
        global $DB;

        //call the core essay initialization code
        parent::get_question_options($question);

        //get the initialization code from the database
        $init_code = $DB->get_record('question_scriptedessay', array('question' => $question->id));

        //load the initialization code
        $question->options->init_code = $init_code->init_code;
    }

    /**
     * Saves this question's extra options to the database.
     */
    public function save_question_options($formdata)
    {
        //use the database
        global $DB;

        //save the core essay question options
        parent::save_question_options($formdata);

        //attempt to get any existing question options that may exist
        $options = $DB->get_record('question_scriptedessay', array('question' => $formdata->id));

        //if no options existed, create an initial, empty options set
        if(!$options)
        {
            //initialize an options row with no data
            $options = new stdClass();
            $options->question = $formdata->id;
            $options->init_code = '';

            //and insert it into the db
            $options->id = $DB->insert_record('question_scriptedessay', $options);
        }

        //save the initialization code
        $options->init_code = $formdata->init_code;

        //finally, save the additional options to the database
        $DB->update_record('question_scriptedessay', $options);
    }

    /**
     * Passes the initialization code to new question instances.
     */
    public function initialise_question_instance(question_definition $question, $questiondata)
    {
        //perform the core essay initialization
        parent::initialise_question_instance($question, $questiondata);

        //and also pass the question its initialization information
        $question->init_code = $questiondata->options->init_code;
    }
}

