<?php

require_once "database.php";  // not sure why this isn't found automagically just by instantiating new below

class theories extends database
{
	public function validate_theory(&$topic,&$theory)
	{
		$return_val = "";	// no news is good news

		$html_topic = trim(htmlentities($topic,ENT_NOQUOTES,"UTF-8"));		// convert HTML characters into their &gt; versions
		$html_theory = trim(htmlentities($theory,ENT_NOQUOTES,"UTF-8"));

		// examine the short version of the theory / topic to see if they are long enough
		if((strlen($topic) < MIN_TOPIC_LENGTH) || (strlen($theory) < MIN_THEORY_LENGTH))
			$return_val = "invalid";

		// examine the long version of the theory / topic to see if they are short enough
		if((strlen($html_topic) > MAX_TOPIC_LENGTH) || (strlen($html_theory) > MAX_THEORY_LENGTH))
			$return_val = "trimmed";

		$topic = substr($html_topic, 0, MAX_TOPIC_LENGTH - 1);		// make sure the $topic and $theory
		$theory = substr($html_theory, 0, MAX_THEORY_LENGTH - 1);	// are returned with html chars converted

		return $return_val;
	}

	public function vote_theory($tid,$vote)
	{
		if(strtolower($vote) == 'spam' && is_admin())		// admins' "vote" for spam means it's spam, no question
		{
			$this->theory_set_status($tid,$vote);
		}
		elseif(logged_in())	// they must be logged in to vote
		{
			// actually add the vote to the vote log
			$vote_sql = "INSERT INTO votelog VALUES(?,?,?,NOW()) ON DUPLICATE KEY UPDATE vote=?, date=NOW()";

			if($statement = $this->mysqli->prepare($vote_sql))
			{
				$statement->bind_param('ddss', $tid, $_SESSION['uid'], $vote, $vote);
				$statement->execute();
				$statement->close();

	//			$count_votes = "SELECT tid, vote, count(*) FROM votelog where tid in(1, 2, 3) group by tid, vote";    // this gets votes for multiple tids if we ever need that
	
				$count_votes = "SELECT tid, vote, count(*) FROM votelog where tid = ? group by vote";
	
				if($statement = $this->mysqli->prepare($count_votes))
				{
					$vote_results = array();
					$statement->bind_param('d', $tid);
					$statement->execute();
					$statement->bind_result($tid, $vote, $count);		// jp.php.net/manual/en/function.mysqli-stmt-bind-result.php
					while ($statement->fetch())
					{
						$vote_results[$vote] = $count;
					}
					$statement->close();
	
					if(debug_for('votes',2))
						print_rob($vote_results,"tid " . $tid);

					$record_totals = "UPDATE theories SET plus_votes = ?, minus_votes = ?, spam_votes = ? WHERE tid = ?";
					if($statement = $this->mysqli->prepare($record_totals))
					{
						$statement->bind_param('dddd',$vote_results['Y'],$vote_results['N'],$vote_results['Spam'],$tid);
						$statement->execute();
						$statement->close();
					}

				}
			}

		}
	}
	public function validate_delete($tid)
	{
		if(is_admin())
			return true;	// admins can delete any theory they deem to be spam or hateful or whatever
		else
		{
			$where=array('tid' => $tid);	// we're going to look up this theory's deets

			$theory = $this->fetch_theories($where);

			if(debug_for('theories',5))
				print_rob($theory,"checking to see if we can delete this");

			if($_SESSION['uid'] == $theory[0]['uid'])
				return true;	// user  can delete his own theory
			else
			{
				// ban user for trying to delete someone else's theory
				return false;
			}


		}
	}

	public function theory_set_status($tid,$status)
	{
		if(debug_for('theories',2))
			echo "theory_set_status called: " . $tid . "=" . $status;

		$status = strtolower($status);  // make sure it's lower case

		switch ($status)
		{
			case 'deleted': $okay_to_set_status = $this->validate_delete($tid);  break;
			case 'spam' : $okay_to_set_status = true;	break;	// not sure when to dissallow this because I'm not sure when it will be called
			default: $okay_to_set_status = false;
		}

		if($okay_to_set_status)
		{
			$status_sql = "UPDATE theories SET status = ? WHERE tid = ?";

			if($statement = $this->mysqli->prepare($status_sql))
			{
				$statement->bind_param('sd', $status, $tid);
				$statement->execute();
				$statement->close();
			}
		}
	}

	public function fetch_theories($where_hash, $wrap_URLs_in_href=false)
	{
		$theory_array = array();		// this will be returned
		$where_array = array();			// this will help parse down $where_hash into a string
		$where_clause = "";			// this will evenutally be the $where_clause

		$count = 0;    // this will add numeric keys to $where_hash so we can refer to that number of numbered params below
		if(isset($where_hash))
		{
			foreach($where_hash as $key => $value)
			{
				$where_hash[$count++] = $value;   // this will allow the bind_param to work for different numbers of parameters!
				$where_array[] = $key . "=?";   // add to the where_array
				$where_types .= $this->cols['theories'][$key];   // add the type so the bind will work.  $this->cols is defined in database class
			}
	 
			$where_clause = "WHERE " . implode (" and ", $where_array);
		}

		if(debug_for('theories',5))
			print_rob($this->cols, "first: cols should show the s or d for all columns");
		if(debug_for('theories',2))
		{
			print_rob($where_clause, "the where_clause:");
			print_rob($where_types, "where types:");
			print_rob($where_hash, "where hash:");
		}

		/********************************************************************************************************************************************/
		/*
		/*  Returning more columns requires adding in three places because I can't find a better way just to return all the data *and* bind_params.
		/*  So, if you want to return more columns, add to (1) the SELECT,  (2) the bind_result, and (3) the $theory_array inside fetch loop.
		/*
		/********************************************************************************************************************************************/

		$_SESSION['start_row'] = ($_GET['start']) ? $_GET['start'] : 0;	// the pagination code will send the requested start row in the URL as a GET param

 		$query = "SELECT SQL_CALC_FOUND_ROWS " .								// so we can do the limit stuff below
/* return  > */		"t.tid, t.uid, u.openid, t.topic, t.theory, t.plus_votes, t.minus_votes, t.spam_votes, " .	// standard items
/* these   > */		"DATE_FORMAT(t.create_date, '" . $_SESSION['date_format'] . "') as create_date, " .		// date is formatted according to $_SESSION; eventually user can change
/* columns > */		"t.create_date as date, t.plus_votes as popular, t.minus_votes as unpopular " .			// these are named to match the values of $_SESSION['sort_by']
			"FROM theories as t left join users as u using(uid)
			{$where_clause}
			ORDER BY " . $_SESSION['sort_by'] . " " . $_SESSION['sort_dir'] . " " .
			"LIMIT " . $_SESSION['start_row'] .  ", " . $_SESSION['theories_per_page'];

		if(debug_for('theories',3))
		{
			print_rob($query, "the query:");
		}

		if($statement = $this->mysqli->prepare($query))
		{
		
			/* regarding the number of parameters, the $where_types string will tell us how many parameters to bind.
			   But I'm not sure what to do if we need to bind more than just the where clause..   */
			switch(strlen($where_types))
			{
				case 0: 	break;  // don't bind anything
				case 1:	$statement->bind_param($where_types, $where_hash[0]); break;
				case 2:	$statement->bind_param($where_types, $where_hash[0], $where_hash[1]); break;
				case 3:	$statement->bind_param($where_types, $where_hash[0], $where_hash[1], $where_hash[2]); break;
				case 4:	$statement->bind_param($where_types, $where_hash[0], $where_hash[1], $where_hash[2], $where_hash[3]); break;
			}
		
			$statement->execute();

			// the $ignore items below are not used when displaying the actual theory, but just used in the ORDER BY clause above
			// http://jp.php.net/manual/en/function.mysqli-stmt-bind-result.php
			$statement->bind_result($tid, $uid, $openid, $topic, $theory, $plus_votes, $minus_votes, $spam_votes, $create_date, $ignore, $ignore, $ignore);  
			while ($statement->fetch())
			{

				if($wrap_URLs_in_href)
				{
					$pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";

					$theory = preg_replace($pattern, '<a href="\0">\0</a>', $theory);

				}
				$theory_array[] = array('tid' => $tid, 'uid' => $uid, 
							'openid' => $openid, 'topic' => $topic, 
							'theory' => $theory, 'plus_votes' => $plus_votes, 
							'minus_votes' => $minus_votes, 'spam_votes' => $spam_votes, 
							'create_date' => $create_date);
			}
			$statement->close();

			$statement=$this->mysqli->prepare("Select FOUND_ROWS()");
			$statement->execute();
			$statement->bind_result($num_records);
			$statement->fetch();
			
			$num_pages = ceil($num_records / $_SESSION['theories_per_page']);

			$_SESSION['num_records'] = $num_records;	// remember the number of records found
			$_SESSION['num_pages'] = $num_pages;		// remember the number of pages required
		}
		if(debug_for('theories',4))
			print_rob($theory_array, "returning this from fetch_theories:");
		return $theory_array;

	}
	public function create_theory($topic,$theory)
	{
		if(!logged_in())
			throw new Exception("Can't write a theory if you're not logged in!");  // this check has already been done, but

		/* write to the DB a new theory
		   No editing of theories, but one can delete theories */

		$sql = "INSERT INTO theories VALUES(NULL,?,?,?,NULL,NULL,NULL,NOW(),'ok')";
		$stmt = $this->mysqli->prepare($sql);

		/* bind the openid with the SQL above */
		$stmt->bind_param('dss', $_SESSION['uid'],$topic,$theory);

		if(debug_for('theories',3))
			print_rob($sql,"sql for adding theory is");

		/* execute prepared statement */
		$stmt->execute();

		/* close statement and connection */
		$stmt->close();
	}

	public function __destruct()
	{
		if(debug_for('theories',3))
			echo "<br/>bye bye theories object!!";
	}
}

?>