<?php
	/**
	 * parent file to prevent
	 * direct access to children file
	 */
	define("PARENT_FILE", dirname(__FILE__));
	require_once 'config.php';

	/**
	 * generate connection from /src/ 
	 * with autoload class
	 */

     $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     function do_something ( $pdo , $order ) {
          $stmt = $pdo -> prepare (
               $order
          );
          $stmt -> execute ();
     }

     if ( $pdo ) {
          /*
          ** initiate database table
          */
          $schemes = array (
              "user" => "CREATE TABLE IF NOT EXISTS user (
			    id varchar(100) not null,
                   name varchar(500) not null,
			    date timestamp default current_timestamp
              )",
              "posts" => "CREATE TABLE IF NOT EXISTS posts (
			    id varchar(100) not null,
			    author varchar(100) not null,
                   content text not null,
			    date timestamp default current_timestamp
              )"
			
          );

          /*
          ** initiate user admin
          */

          foreach ( $schemes as $key =>  $value ) {
               do_something ( $pdo , $value );
          }

		$stmt = $pdo -> prepare ( " select * from posts ");
		$stmt -> execute ();

		var_dump ( $stmt -> fetchAll () );
          

		echo "<br />Database connected";
     }
