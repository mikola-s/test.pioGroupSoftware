<?php


$logged_user = new User;

echo $logged_user->login ."<br>" ;
echo $logged_user->access;

/**
 * 
 */
class User 
{
	public $password;
	public $login = 'Guest';
	public $access = 'read';

	public function __construct(){

	}

	//добавить пользователя 
	public function add(){

	}

	//поиск пользователя 
	public function find(){

	}

	//сохранить пользователя
	public function save(){

	}

	//удалить пользователя
	public function delete(){
		
	}
}


/**
 * 
 */
class news
{
	public $name, $date, $description, $full_text, $id;

	function __construct()
	{
		# code...
	}

	//
	public function add($value='')
	{
		# code...
	}

	//
	public function delete($value='')
	{
		# code...
	}

	public function edit($value='')
	{
		# code...
	}

	public function save($value='')
	{
		# code...
	}
}

/**
 * 
 */


class page_element
{


	
	function __construct()
	{
		# code...
	}
}

