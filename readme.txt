Set of functions to automate the process of creating simple HTML forms

HTML generated is in the format of:

<div class='form-row'>
		<div class='form-label'><label for='foo'>Foo</label>: </div>
		<div class-'form-field'><input type='blah' name='foo' id='foo' /></div>
</div>

Works best when the divs are floated, e.g.:

label {font-weight: bold}
.form-row {float: left; clear: both; padding: 10px 0 10px 0;}
.form-field {float: left; }
.form-label {float: left; width:200px; text-align: right; margin-right:20px;}