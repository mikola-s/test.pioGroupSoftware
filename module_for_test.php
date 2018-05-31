<?php
include_once('StartSet.php');
session_start();






$logged_user = new user;
$page_element = new page_elements;

$page_element->head(); //шапка стриницы

		echo '<pre>';
		echo '<br><br>post<br>';
		print_r ($_POST);
		echo '</pre>';
//главная станица после авторизации

if (!empty($_POST) && (isset($_POST['submit']))) {
		if ($_POST['submit'] == 'login') {
			$login_from_post = htmlentities($_POST['login']);
			$password_from_post = htmlentities($_POST['password']);
			if ($logged_user->autorization($login_from_post, $password_from_post)) {
				$page_element->user_login($login_from_post, TRUE);
			} 	
			else {
				$page_element->user_login($login_from_post, FALSE);
			}
		} elseif ($_POST['submit'] == 'exit') {
			$logged_user->update_session();
			$page_element->head();
			$page_element->user_login($logged_user->login, FALSE);
		}	
		//$page_element->user_login($logged_user->login);
	} 

elseif (empty($_POST)) {
	$logged_user->update_session();
	$page_element->head();
	$page_element->user_login($logged_user->login, FALSE);

	}

//----------------------------------------
		if (isset($_SESSION)) {
			echo '<pre>';
			echo '<br><br>sessipn<br>';
			print_r ($_SESSION);
			echo '</pre>';
		}
//--------------------------------------

// if (empty($_GET)) {
	
 	echo $page_element->news_list($logged_user->user_data());
// } elseif (!empty($_GET) && ($_GET{
// 	# code...
// }


	


	// echo $logged_user->login ."<br>" ;
	// echo $logged_user->access;
		// echo "login ".$logged_user->login ."<br>" ;
		// echo "access ".$logged_user->access;


/**
 * 
 */
class user 
{
	public $password;
	public $login = 'Guest';
	public $access = 'read';

	//запрашивает из БД данные пользователя
	public function user_data()
	{
		$mysqli = $this->mysql_open();
		$query = "SELECT login, access FROM user WHERE login = '$this->login'";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$user_data = $mysqli_rezult->fetch_assoc();
		}
		$mysqli->close();
		return $user_data;
		
		echo "<br><pre>user_data ";
			print_r($user_data);
		echo "</pre>";
	}

	//
	public function autorization($login, $password)
	{
		$mysqli = $this->mysql_open();
		$pass_md5 = md5($password);
		$query = "SELECT login, password, access FROM user WHERE login = '$login'";
		$mysqli_rezult = $mysqli->query($query);
		if (!empty($mysqli_rezult)) {
			$user_data = $mysqli_rezult->fetch_assoc();
			$mysqli->close();
			if (isset($user_data['password']) &&
				($user_data['password'] == $pass_md5)) {
				if (isset($_SESSION)) {
					$this->login = $user_data['login'];
					$this->access = $user_data['access'];
					$this->password = $user_data['password'];
					$this->update_session();
				}
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			$mysqli->close();
			return FALSE;
		}
		
	}

	//обновление параметров сессии
	public function update_session()
	{
		$_SESSION['login'] = $this->login;
		$_SESSION['access'] = $this->access;
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
		require("mysql_login.php");
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
		require("mysql_login.php");
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

	public function user_login($user_name, $autorization)
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
		elseif ($autorization == TRUE)
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
	    elseif ($autorization === FALSE)
		{
		echo <<<_USER_ENTERED
		<div class="login_form">
				<h4 id="ahtung">wrong login or password</h4>
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
_USER_ENTERED;

		}
	}


	//лента новостей
	public function news_list($user_data)
	{
			echo '<pre> user data';
				print_r($user_data);
			echo '</pre>';
		$mysqli = $this->mysql_open();
		$query = "SELECT id, header, description, date FROM news";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$news_data = $mysqli_rezult->fetch_all(MYSQLI_ASSOC);
		}
		$mysqli->close();
		echo '<div name="news_descriptions_list" 
			class="col-md-6 news_descriptions_list">';
		foreach ($news_data as $row_num => $row){
			$this->list_block($user_data, $row);
		}
		echo '</div>';

		

	}

	//короткая новость
	public function list_block($user_data, $news_data)
	{
		
			echo '<pre> user data';
				print_r($user_data);
			echo '</pre>';
		echo '<div class="news_block">';
		if ($user_data['access'] == 'full') {
			echo <<<_EDIT_BUTTON
				    <form method="post" class="edit_button">
	        			<input type="hidden" name="news_id" value="{$news_data['id']}">
	        			<input type="submit" name="submit" value="edit">
	      			</form>
_EDIT_BUTTON;
	      		
		}

		$file = str_replace(__DIR__, '',__FILE__) ."?news_id=". $news_data['id'] .
			"&submit=full_news";
		echo <<<_LIST_BLOCK
					<a href="$file">
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
		require("mysql_login.php");
		$mysqli = new mysqli($hostname, $username, $password, $database);
		if ($mysqli->connect_error) {
   			exit($mysqli->connect_error);
  		}
  		return $mysqli;
	}
}

