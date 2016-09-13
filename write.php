<?php
require_once "common.php";      // super basic stuff, including openID login
require_once "initialize.php";  // this is after they are logged in
require_once "theories.php";

/*  Write theories here */

	$theory = new theories();
	$write_theory = array();			// this will tell what write.template.php can display

	if(!logged_in())
	{
		$error = "If you want to write a theory, you must confirm your OpenID.";
		$write_theory['preview'] = false;	// write.template.php should not show the theory preview, but just the error above
		$write_theory['form'] = false;		// write.template.php should not show the new theory form, but just the error above
	}
	else
	{
		$buttons = array('preview');   // tells what buttons to use in form on write.template.php.    default is just preview because we must preview at least one time
		$write_theory['form'] = true;	// it's okay for write.template.php to show the new theory form

		if(debug_for('theories',3))
			print_rob($_POST,"this was sent in the post.");

		$action = $_POST['action'];    // figure out what we are supposed to do.  If this is null, then user just came to the screen, so just show the form

		if(isset($action))
		{
			$theory_topic = $_POST['topic'];    // get the data sent by user  (form is in write.template.php)
			$theory_text  = $_POST['theory'];    // get the data sent by user

			// no matter what, we always want to validate the data
			$theory_validation = $theory->validate_theory($theory_topic,$theory_text);    // this will actually modify the topic and theory (strip HTML, etc)

			if(debug_for('theories',5))
				print_rob($valid_theory,"result from validate_theory:");
	
			// depending on the results of the data validation, set messages for write.template.php or write the data to db
			switch($theory_validation)
			{
				case "invalid" : $error = "Topic and/or theory too short.  They must be at least " . MIN_TOPIC_LENGTH . " and " . MIN_THEORY_LENGTH . " characters, respectively.";
					break;
				case "trimmed" : $msg = "Topic and/or theory was too long and has been trimmed.  Max " . MAX_TOPIC_LENGTH . " and " . MAX_THEORY_LENGTH . " characters, respectively.";
					$write_theory['preview'] = true;	// it's okay for write.template.php to show the new theory preview
					break;
				default:
					if($action == "submit")
					{
						try
						{
							$theory->create_theory($theory_topic,$theory_text);
						}
						catch (Exception $e)
						{
							print_rob($e);
						}
						// need to check results
						$success = "Theory saved.";
						$write_theory['form'] = false;		// we have saved the theory, so don't print the form again
						$write_theory['preview'] = true;	// it's okay for write.template.php to show the new theory preview
					}
					else
					{
						$write_theory['preview'] = true;	// it's okay for write.template.php to show the new theory preview
						$buttons = array('preview', 'submit');
					}
					break;
			}


		}
			

		/* set these depending on results of data validation above.  */
		$theory_form_submit_buttons = "";	// this will be displayed by write.template.php at the bottom of the new theory form
		foreach($buttons as $but)
		{
			switch($but)
			{
				case "preview" :
					$theory_form_submit_buttons .= "<input type='submit' name='action' value='preview'>";
					break;
				case "submit" :
					$theory_form_submit_buttons .= "<input type='submit' name='action' value='submit'> ";
					break;
			}
		}

	}

	include "template/write.template.php";
?>
