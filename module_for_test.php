<?php




/**
 * 
 */
class User 
{
	public $password;
	public $login = 'Guest';
	public $access = 'read';

	public function __construct(argument)
	{

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
	public function delete()
	{
		# code...
	}
}


/**
 * 
 */
class news
{
	public $name, $date, $description, $full_text;

	function __construct(argument)
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
class pages
{
	
	function __construct(argument)
	{
		# code...
	}
}

