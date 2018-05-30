<?php
include_once('StartSet.php');

$logged_user = new User;
$page_element = new page_elements;

//страница до входа пользователя
if (!empty($_POST) && (isset($_POST['submit'])) && ($_POST['submit'] == 'login')) 
	{
		echo '<pre>';
		print_r ($_POST);
		echo '</pre>';
		//проверка логина и пароля

		//вывод формы вывода
		$logged_user->login = $_POST['login'];
		$page_element->head();
		$page_element->user_login($logged_user->login);
	
		echo '<br><br><br>';
		echo "login ".$logged_user->login ."<br>" ;
		echo "access ".$logged_user->access;
	} 
	elseif (empty($_POST) ||
		(!empty($_POST) && (isset($_POST['submit'])) && 
		($_POST['submit'] = 'exit')))
	{
	//----------------------------------------------
	// echo '<br><br><br>';
	// echo '<pre>';
	// print_r ($_POST);
	// echo '</pre>';
	//---------------------------------------------


	$page_element->head();
	$page_element->user_login($logged_user->login);

	// echo $logged_user->login ."<br>" ;
	// echo $logged_user->access;
	}
//----------------здесь надо поставить нормальный доступ
	echo $page_element->news_list('full');




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

	//открывает базу данных и проверяет открытие
	public function mysql_open()
	{
		require_once("mysql_login.php");
		$mysqli = new mysqli($hostname, $username, $password, $database);
		if ($mysqli->connect_error) {
   			exit($mysqli->connect_error);
  		}
  		return $mysqli;
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
	public function add()
	{

		$this->mysqli_open = '';
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
	

	//открывает базу данных и проверяет открытие
	public function mysql_open()
	{
		require_once("mysql_login.php");
		$mysqli = new mysqli($hostname, $username, $password, $database);
		if ($mysqli->connect_error) {
   			exit($mysqli->connect_error);
  		}
  		return $mysqli;
	}
}

/**
 * 
 */


class page_elements
{
	
	function __construct()
	{
		# code...
	}


	//вывод шапки сайта 
	public function head()
	{
		echo <<<_HTML_head
			<!DOCTYPE HTML>
			<head>
				<title>OUCH news</title>
				<meta charset="utf-8">
				
				<!-- подключени bootstrap-->
				<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
				<link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">
				<script src="./bootstrap/js/bootstrap.min.js"></script>
				  
				<!-- подключение своих стилей css -->
				<link rel="stylesheet" href="style.css">
			</head>
			<body>
				<!-- шапка сайта -->
				<div id="page_header">
				    <h1> OUCH News </h1>
				</div>
_HTML_head;
	}

	public function user_login($user_name)
	{
		if ($user_name == 'Guest') 
		{
			
		echo <<<_USER_LOGIN
		<div class="login_form">
			<h4>Hello $user_name!</h4>
	    	<form name="sign_in" method="post">
	    		<input 
	    			type="text" 
	    			name="login" 
	    			placeholder="enter your login">
	    		<input 
	    			type="password" 
	    			name="password" 
	    			placeholder="enter your password">
			    <input 
			    	type="submit"
			    	name="submit"  
			    	value="login">
		    </form>
	    </div>
_USER_LOGIN;

		} 
		else 
		{
		echo <<<_USER_ENTERED
		<div class="login_form">
			<h4>Hello $user_name!</h4>
	    	<form name="sign_in" method="post">
	    		<input 
			    	type="submit"
			    	name="submit"  
			    	value="exit">
		    </form>
	    </div>
_USER_ENTERED;

		}
	}


	//лента новостей
	public function news_list($user_access)
	{
		$mysqli = $this->mysql_open();
		$query = "SELECT id, header, description, date FROM news";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$news_data = $mysqli_rezult->fetch_all(MYSQLI_ASSOC);
		}
		$mysqli->close();
		echo '<div name="news_descriptions_list" 
			class="col-md-6 news_descriptions_list">';
		foreach ($news_data as $row_num => $row){
			$this->list_block($user_access, $row);
		}
		echo '</div>';

		
			//echo '<br>'. count($news_data);
			// 	print_r ($news_data);
			// echo '<pre>';
			// 	print_r($row);
			// echo '</pre>';

	}

	//короткая новость
	public function list_block($user_access, $news_data)
	{
		
		echo '<div class="news_block">';
		if ($user_access == 'full') {
			echo <<<_EDIT_BUTTON
				    <form class="edit_button">
	        			<input type="hidden" name="news_id" value="{$news_data['id']}">
	        			<input type="submit" name="submit" value="edit">
	      			</form>
_EDIT_BUTTON;
		}
		echo <<<_LIST_BLOCK
					<a href="head.html?news_id={$news_data['id']}&submit=full_news">
		        		<h4 name="news_header" class="news_name">{$news_data['header']}</h4>
		        		<p class="news_date">{$news_data['date']}</p>
		        		<p class="news_description">{$news_data['description']}</p>     
		     		</a>
    			</div>
_LIST_BLOCK;
		
	}
	public function edit_button($news_id)
	{
	
		echo <<<_EDIT_BUTTON
	    	<form class="edit_button">
		        <input type="hidden" name="news_id" value="$news_id">
		        <input type="submit" name="submit" value="edit">
	      	</form>
_EDIT_BUTTON;

	}
	public function add_news_button($value='')
	{
		# code...
	}

	//открывает базу данных и проверяет открытие
	public function mysql_open()
	{
		require_once("mysql_login.php");
		$mysqli = new mysqli($hostname, $username, $password, $database);
		if ($mysqli->connect_error) {
   			exit($mysqli->connect_error);
  		}
  		return $mysqli;
	}
}

