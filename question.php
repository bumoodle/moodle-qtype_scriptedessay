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
 * Essay question definition class.
 *
 * @package    qtype
 * @subpackage essay
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/essay/question.php');
require_once($CFG->dirroot.'/question/type/scripted/question.php');

/**
 * Represents a scripted essay question.
 *
 * @copyright  2012 Kyle J. Temkin, Binghamton University, <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_scriptedessay_question extends qtype_essay_question 
{

    /**
     * Initializes a question attempt and generates randomized values using the initialization MathScript.
     */
    public function start_attempt(question_attempt_step $step, $variant)
    {
        print_object($this);

        list($errors, $vars, $funcs) = qtype_scripted_question::execute_script($this->init_code, $this->questiontext);

        //store the list of variables after the execution, for storage in the database
        $step->set_qt_var('_vars', qtype_scripted_question::safe_serialize($vars));
        $step->set_qt_var('_funcs', qtype_scripted_question::safe_serialize($funcs));
    	
    	//store a local copy of the EvalMath state
    	$this->vars = $vars;
        $this->funcs = $funcs;
    }

    /**
     *  Restores the randomized variables from the first time this question was utilized.
     */
    public function apply_attempt_state(question_attempt_step $step)
    {
        //restore the varaibles and functions created by this question instance
        $this->vars = qtype_scripted_question::safe_unserialize($step->get_qt_var('_vars'));
        $this->funcs = qtype_scripted_question::safe_unserialize($step->get_qt_var('_funcs'));
    }

    /**
     * Inserts the varaibles for the given question text, then calls the basic formatter.
     * 
     */
	public function format_questiontext(question_attempt $qa)
	{
		//get a list of varaibles created by the initialization MathScript 
		$vars = qtype_scripted_question::safe_unserialize($qa->get_last_qt_var('_vars'));

		//get the quesiton text, with all known variables replaced with their values
        $questiontext = qtype_scripted_question::replace_variables($this->questiontext, $vars);

		//run the question text through the basic moodle formatting engine
		return $this->format_text($questiontext, $this->questiontextformat, $qa, 'question', 'questiontext', $this->id);

    }

}
