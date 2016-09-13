<?php

class database
{
	public $mysqli;

	/************************************************************************************************************
	/*
	/* $cols will describe the tables in terms of s/d for the column types.  That way, we can easily bind 
	/* the correct type if we simply know the (table name and the) column name.
	/*
	/************************************************************************************************************
	/* ((it would be quite cool if this could be automated.  It shouldn't run every time this database object
	/*   is instantiated, but every time the DB tables change, this needs to match it.))			    
	/************************************************************************************************************/
	public $theories_columns = array('tid' => 'd', 'uid' => 'd', 'topic' => 's', 'theory' => 's', 'plus_votes' => 'd', 'minus_votes' => 'd', 'spam_votes' => 'd',
					'create_date' => 's', 'status' => 's');
	public $users_columns = array('uid' => 'd', 'openid' => 's', 'create_date' => 's', 'login_date' => 's',	'total_cents' => 'd', 'donated' => 's', 'donate_date' => 's',
					'theories_written' => 'd', 'theories_voted' => 'd');
	public $cols = array('theories' => array(), 'users' => array());   // prepare arrays for the constructor to populate with the above hashes

	public function __construct()
	{
		$this->mysqli = new mysqli(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DB_NAME);

		/* check connection */
		if (mysqli_connect_errno())
		    throw new Exception("Connect failed: " . mysqli_connect_error());
		
		if(debug_for('db',3))
			printf("Host information: %s\n", $this->mysqli->host_info);
		$this->mysqli->set_charset('utf8');

		/* now store the column types to make binding values easier */
		$this->cols['theories'] = $this->theories_columns;
		$this->cols['users'] = $this->users_columns;
	}

	public function prepare($sql)
	{
		$this->mysqli->prepare($sql);
		if ($this->db->mysqli->errno<>0)
			throw new Exception("in database.php " . $this->db->mysqli->errno.": ".$this->db->mysqli->error);
	}

	public function __destruct()
	{
		/* close connection */
		$this->mysqli->close();

		if(debug_for('db',3))
			echo "bye bye database object!!";
	}
}
?>