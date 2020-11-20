<?php
//error_reporting(E_ERROR);
// DB Class for easy manipulation
// Class Vars Definition
class mysqlDB
{
	//var $myserver = "internal-db.s216053.gridserver.com";
	//var $myuser = "db216053_cfdi";
	//var $mypass = "CFDimix16-";
	var $mydb = "cake";
	var $myserver = "localhost";
	var $myuser = "root";
	var $mypass = "";
	var $rowstotal;
	var $connect;
	var $search;
	var $row = array();
	// DB Object constructor
	function mysqlDB() 
	{
	  // $this->connect = mysql_connect($this->myserver, $this->myuser, $this->mypass);
	  $this->connect = mysqli_connect( $this->myserver, $this->myuser, $this->mypass, $this->mydb );
	  mysqli_set_charset($this->connect,"utf8");

	  if (!$this->connect) 
	  { 
	  	echo "<pre>"; echo "SERVIDOR EN MANTENIMIENTO, DISCULPE LAS MOLESTIAS"; exit();
		exit;
	  }
	  return $this->connect;
	}

	//  DB Query execution
	function query($sqlstring) 
	{
	  //$this->search = mysql_query($sqlstring, $this->connect);
	  $this->search = mysqli_query($this->connect, $sqlstring);
	  if(!$this->search)
	  {
		// echo "[Q_Q]";
	  }
	  return $this->search;
	}

	//  DB Fetch from ResultSet
	function fetch()
	{
		if (isset($this->search)) 
		{
			return $this->row = mysqli_fetch_array($this->search);
		}
		else 
		{
			// echo "[RW_ST]";
		}
    }
	
	// ResultSet Count Rows
	function rows() 
	{
      $this->rowstotal = mysqli_num_rows($this->search);
	  return $this->rowstotal;
    }
	
	// Escapa las cadenas para SQL
	function real_escape($var)
	{
		return mysqli_real_escape_string($this->connect, $var);
	}

	// Buscar errores
	function error()
	{
		if ( mysqli_error($this->connect) ) { return true; }
		else { return false; } 
	}
	
	// Mostrar errores
	function error_show()
	{
		return mysqli_error($this->connect);
	}
	
	// recupera id tras insercion
	function insertid()
	{
		return mysqli_insert_id($this->connect);
	}
}?>