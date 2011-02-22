<?php
/**
 * Set of functions to automate the process of creating simple HTML forms
 *
 * HTML generated is in the format of:
 *
 * <div class='form-row'>
 *		<div class='form-label'><label for='foo'>Foo</label>: </div>
 *		<div class-'form-field'><input type='blah' name='foo' id='foo' /></div>
 * </div>
 *
 * Works best when the divs are floated, e.g.:
 *
 * label {font-weight: bold}
 * .form-row {float: left; clear: both; padding: 10px 0 10px 0;}
 * .form-field {float: left; }
 * .form-label {float: left; width:200px; text-align: right; margin-right:20px;}
 *
 * @author Benjamin J. Balter
 * @version 1.0
 */

/**
 * Generate the form by calling the sub functions 
 *
 * Questions are passed in the form of array(array($name, $description, $type, $value, $choices, $size, $args)).  See make_field() for more details
 *
 * @params string $name The Name of the Form to Create
 * @parmas array $questions a multi-dimensional array of questions, if ommitted, function will simply create opening form tag
 * @params string $method Method by which to submit form, either post or get
 * @params string $action Action attribute of form tag (form target)
 * @params string $args Arguments to tack on to the end of the form tag, e.g., onSubmit functions
 * 
 */
 function make_form($name,$questions=array(),$method="post",$action="",$args="",$closingTag = true) {
	echo '<form name="' . $name . '" id="' . $name . '" method="' . $method .'" ';
	if ($action != "") echo 'action="' . $action . '" ';
	echo $args . '>' . "\n";
	foreach ($questions as $question) {
		$question = array_pad($question,9,null);
		make_field($question[0],$question[1],$question[2],$question[3],$question[4],$question[5],$question[6], $question[7], $question[8]);
	}
	if (sizeof($questions)>0 && $closingTag) echo '</form>' . "\n";
}

/**
 * Generate a form input field
 *  
 * @param string $name The name of the input field
 * @param string $desc The label to associate with the field
 * @param string $type The type of input, can be text, textarea, select, radio, checkbox, hidden, password, file, submit or reset
 * @param bool $required if the field is required
 * @param string $value The value to set the field to on load
 * @param array $choices Choices to pass to radio, select, and checkbox inputs
 * @param int $size Size param to pass to input tag
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 * @param string $helptext Helptext
 */
function make_field($name, $desc, $type, $required = false, $value="", $choices=array(), $size="", $args="", $helptext = '') {
	echo "\t" . '<div class="form-row ' . $type. '">' . "\n";
		if ($type != "hidden") {
			echo "\t\t" . '<div class="form-label">' . "\n";
			if (strlen($desc)>0)	{
				echo "\t\t\t" . '<label for="' . $name .'"';
				if ($required) echo ' class="required"';
				echo '">' . $desc . "</label>:";
			}
			echo "&nbsp;\n";
			if ($helptext != '') echo "\t\t\t" . '<div class="helptext">' . $helptext . '</div>' . "\n";
			echo "\t\t" . '</div>' . "\n";
		}
		echo "\t\t" . '<div class="form-field">' . "\n";
		switch($type) {
			case 'text':
				make_text_field($name,$value,$size,$args);
			break;
			case 'textarea':
				make_textarea_field($name,$value,$size,$args);
			break;
			case 'select':
				make_select_field($name,$value,$choices,$args);
			break;
			case 'radio':
				make_radio_field($name,$value,$choices,$args);
			break;
			case 'checkbox':
				make_checkbox_field($name,$value,$choices,$args);	
			break;
			case 'hidden':
				make_hidden_field($name,$value);
			break;
			case 'password':
				make_password_field($name,$value,$size,$args);
			break;
			case 'file':
				make_file_field($name,$value,$size,$args);
			break;
			case 'submit':
				make_submit_button($name,$value,$args);
			break;
			case 'reset':
				make_reset_button($name,$value,$args);
			break;
		}
		echo "\t\t" . '</div>' . "\n";
	echo "\t" . "</div>\n";
}

function make_text_field($name,$value,$size,$args) {
	echo "\t\t\t" . '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '"';
	if ($size != "") echo ' size="' . $size .'"';
	echo ' ' . $args . " />\n";
}

/**
 * Generates a textarea input field
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param int $size Size param to pass to input tag
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_textarea_field($name,$value,$size,$args) {
	echo "\t\t\t" . '<textarea name="' . $name . '" id="' . $name . '"';
	if ($size != "") {
		$size = explode(",",$size);
		echo ' rows="' . $size[0] .'" ';
		echo 'cols="' . $size[1] .'" ';
	}
	echo ' ' . $args . '>' . $value . "</textarea>\n";
}

/**
 * Generates a select drop-down
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param array $choices Choices to pass to radio, select, and checkbox inputs
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_select_field($name,$value,$choices,$args) {
	echo "\t\t\t" . '<select name="' . $name . '" id="' . $name . '" ' . $args . ">\n";
	foreach ($choices as $choice=>$desc) {
		echo "\t\t\t\t" . '<option value="' . $choice . '"';
		if ($choice == $value) echo ' selected="true"';
		echo '>' . $desc . "</option>\n";
	}
	echo "\t\t\t" . "</select>\n";
}

/**
 * Generates a set of radio inputs
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param array $choices Choices to pass to radio, select, and checkbox inputs
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_radio_field($name,$value,$choices,$args) {
	foreach ($choices as $choice=>$desc) {
		echo "\t\t\t" . '<input type="radio" name="' . $name . '" id="' . $name . '[' . $choice . ']" ' . $args . " value='" . $choice. "'";
		if ($choice == $value) echo ' checked="true"';
		echo ' /><label for="' . $name . '[' . $choice . ']">' . $desc . "</label><br />\n";
	}
}

/**
 * Generates a set of checkboxes
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param array $choices Choices to pass to radio, select, and checkbox inputs
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_checkbox_field($name,$value,$choices,$args) {
	foreach ($choices as $choice=>$desc) {
		echo "\t\t\t" . '<input type="checkbox" name="' . $name . '[' . $choice . ']" id="' . $name . '[' . $choice . ']" ' . $args;
		if ($choice == $value) echo ' checked="checked"';
		echo ' /><label for="' . $name . '[' . $choice . ']">' . $desc . "</label><br />\n";
	}
}

/**
 * Generates a hidden field
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to
 */
function make_hidden_field($name,$value) {
	echo "\t\t\t" . '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />' . "\n";
}

/**
 * Generates a password input 
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param int $size Size param to pass to input tag
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_password_field($name,$value,$size,$args) {
	echo "\t\t\t" . '<input type="password" name="' . $name . '" id="' . $name . '" value="' . $value . '"';
	if ($size != "") echo 'size="' . $size .'"';	
	echo ' ' . $args . ' />' . "\n";
}

/**
 * Generates a file upload field
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param int $size Size param to pass to input tag
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_file_field($name,$value,$size,$args) {
	echo "\t\t\t" . '<input type="file" name="' . $name . '" id="' . $name . '" value="' . $value . '"';
	if ($size != "") echo 'size="' . $size .'"';
	echo ' ' . $args . ' />' . "\n";
}

/**
 * Generatea submit button
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_submit_button($name,$value,$args) {
	echo "\t\t\t" . '<input type="submit" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $args . " />\n";
}

/**
 * Generates a reset button
 *
 * @param string $name The name of the input field
 * @param string $value The value to set the field to on load
 * @param string $args Additional arguments to pass to input tag such as onClick or onChange functions
 */
function make_reset_button($name,$value,$args) {
	echo "\t\t\t" . '<input type="reset" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $args . " />\n";
}

?>