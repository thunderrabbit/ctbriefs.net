<?php

require_once "database.php";  // not sure why this isn't found automagically just by instantiating new below

class users extends database
{
	public function user_logged_in($openid)
	{
		/* write to the DB a new user or update the login_date if already in the DB */
		$sql = "INSERT INTO users VALUES(NULL,?,NOW(),NOW(),0,'no',NULL,0,0,'ok') ON DUPLICATE KEY UPDATE login_date=NOW()";
		$stmt = $this->mysqli->prepare($sql);

		/* bind the openid with the SQL above */
		$stmt->bind_param('s', $openid);

		if(debug_for('db',3))
			print_rob($sql,"sql for adding user is");

		/* execute prepared statement */
		$stmt->execute();

		if(debug_for('db',2))
			echo "this user's id is: " . $this->mysqli->insert_id;


		/* this will let the session know who is logged in */
// already done by initialize.php		$_SESSION['openid'] = $openid;
		$_SESSION['uid'] = $this->mysqli->insert_id;	//  mysqli provides this so we don't have to do bind_variables
		/* close statement and connection */

		$stmt->close();

		/*******************************************************************************************
		/*
		/*  Now read the user's record to get their preferences and see if they have been banned
		/*
		/*******************************************************************************************/

		$read_user_sql = "SELECT uid, openid, user_status FROM users WHERE uid = ?";

		if(debug_for('db',3))
			print_rob($read_user_sql,"sql for reading user info is");

		$read_user_stmt = $this->mysqli->prepare($read_user_sql);

		/* bind the openid with the SQL above */
		$read_user_stmt->bind_param('d', $_SESSION['uid']);

		/* execute prepared statement */
		$read_user_stmt->execute();

		$read_user_stmt->bind_result($uid, $openid, $user_status);

		$read_user_stmt->fetch();

		$_SESSION['date_format'] = "%d %M %Y";          //  THIS SHOULD BE READ FROM users DB

		if(debug_for('users',3))
		{
			$debug_data = "{$openid} (uid {$uid}) user_status is {$user_status}";
			print_rob($debug_data, "we just found out:");
		}

		return $user_status;	// if they are ok or if they are banned

	}

	public function num_users()
	{
		$sql = "SELECT count(*) FROM users WHERE uid > 8";

		$result = $this->mysqli->query($sql);

		$row = $result->fetch_row();	// there is only one row so we don't put this in a loop

		return $row[0];			// there is only one value
	}

	// count the number of users who have voted
	public function num_voting_users()
	{
		$sql = "SELECT count(*) FROM (select * FROM users left join votelog using(uid) WHERE tid is NOT NULL AND uid > 8 group by uid) as voters";	// how many voted?

		$result = $this->mysqli->query($sql);
		if($result)
		{
			$row = $result->fetch_row();	// there is only one row so we don't put this in a loop
			return $row[0];			// there is only one value
		}
		else
			return 0;
	}

	// count the number of users who have written theories
	public function num_theorists()
	{
		$sql = "SELECT count(*) FROM (
					SELECT uid 
					FROM users left join theories as t using(uid) 
					WHERE tid is NOT NULL 
					AND t.status = 'ok' 
					AND uid > 8 
					GROUP BY uid) as theorists";
		
		$result = $this->mysqli->query($sql);
		if($result)
		{
			$row = $result->fetch_row();	// there is only one row so we don't put this in a loop
			return $row[0];					// there is only one value
		}
		else
			return 0;
	}

	public function __destruct()
	{
		if(debug_for('users',3))
			echo "<br/>bye bye user object!!";
	}
}

?>