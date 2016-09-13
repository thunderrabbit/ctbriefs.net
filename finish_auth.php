<?php

require_once "common.php";
require_once "users.php";    // Otherwise the insantiation of new users() below doesn't work, but why not?

function run() {
    $consumer = getConsumer();

    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);

	if(debug_for('openid',2))
	{
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}

    $_SESSION['openid response'] = $response->status;

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        // This means the authentication succeeded; extract the
        // identity URL and Simple Registration data (if it was
        // returned).
        $openid = $response->getDisplayIdentifier();
        $esc_identity = htmlspecialchars($openid, ENT_QUOTES);

	$_SESSION['openid'] = $esc_identity;	// record the openid which they confirmed

        $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

        $sreg = $sreg_resp->contents();

	if(debug_for('openid',2))
	{
		echo "<pre>";
		print_r($sreg);
		echo "</pre>";
	}

        if (@$sreg['email']) {
            $success .= "  You also returned '".$sreg['email']."' as your email.";
        }

        if (@$sreg['nickname']) {
            $success .= "  Your nickname is '".$sreg['nickname']."'.";
        }

        if (@$sreg['fullname']) {
            $success .= "  Your fullname is '".$sreg['fullname']."'.";
        }

	unset($success);	// we don't want any of their deets above.

/*    I don't know what PAPE is for, so I'm not showing this part
	$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

	if ($pape_resp) {
	  if ($pape_resp->auth_policies) {
	    $success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

	    foreach ($pape_resp->auth_policies as $uri) {
	      $success .= "<li><tt>$uri</tt></li>";
	    }

	    $success .= "</ul>";
	  } else {
	    $success .= "<p>No PAPE policies affected the authentication.</p>";
	  }

	  if ($pape_resp->auth_age) {
	    $success .= "<p>The authentication age returned by the " .
	      "server is: <tt>".$pape_resp->auth_age."</tt></p>";
	  }

	  if ($pape_resp->nist_auth_level) {
	    $success .= "<p>The NIST auth level returned by the " .
	      "server is: <tt>".$pape_resp->nist_auth_level."</tt></p>";
	  }

	} else {
	  $success .= "<p>No PAPE response was sent by the provider.</p>";
	}
*/
    }

}

run();

require_once 'index.php';

?>