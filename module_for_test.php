<?php
include_once('StartSet.php');
session_start();






$user = new user_class;
$html_part = new page_elements;
$news = new news_class;

$html_part->head(); //шапка стриницы

		echo '<pre>';
		echo '<br><br>post<br>';
		print_r ($_POST);
		print_r ($_GET);
		echo '</pre>';
//главная станица после авторизации

if (!empty($_POST) && (isset($_POST['submit']))) {
		if ($_POST['submit'] == 'login') {
			$login_from_post = htmlentities($_POST['login']);
			$password_from_post = htmlentities($_POST['password']);
			if ($user->autorization($login_from_post, $password_from_post)) {
				$html_part->form_login($login_from_post, TRUE);
			} 	
			else {
				$html_part->form_login($login_from_post, FALSE);
			}
		} elseif ($_POST['submit'] == 'exit') {
			$user->update_session();
			$html_part->head();
			$html_part->form_login($user->login, FALSE);
		} elseif ($_POST['submit'] == 'edit') {
			//код для правки новостей
		}	
		//$html_part->form_login($user->login);
	} 

elseif (empty($_POST)) {
	$user->update_session();
	$html_part->head();
	$html_part->form_login($user->login, FALSE);

}

//----------------------------------------
		// if (isset($_SESSION)) {
		// 	echo '<pre>';
		// 	echo '<br><br>sessipn<br>';
		// 	print_r ($_SESSION);
		// 	echo '</pre>';
		// }
//--------------------------------------

if (empty($_GET)) {
 	echo $html_part->short_news_list($user->access, $news->short_news());
} elseif (isset($_GET['submit']) && ($_GET['submit'] == 'full_news')) {
	echo "full_news";
}

	


	// echo $user->login ."<br>" ;
	// echo $user->access;
		// echo "login ".$user->login ."<br>" ;
		// echo "access ".$user->access;


/**
 * 
 */
class user_class 
{
	public $password;
	public $login = 'Guest';
	public $access = 'read';

	//запрашивает из БД данные пользователя
	//скорее всего не нужна
	public function user_data()
	{
		$mysqli = $this->mysql_open();
		$query = "SELECT login, access FROM user WHERE login = '$this->login'";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$user_data = $mysqli_rezult->fetch_assoc();
		}
		$mysqli->close();
		return $user_data;
		
		// echo "<br><pre>user_data ";
		// 	print_r($user_data);
		// echo "</pre>";
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
class news_class
{
	public $name, $date, $description, $full_text, $id;


	//
	public function short_news()
	{
		$mysqli = $this->mysql_open();
		$query = "SELECT id, header, description, date FROM news";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$short_news_data = $mysqli_rezult->fetch_all(MYSQLI_ASSOC);
		}
		$mysqli->close();
		return $short_news_data;

	}
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

	public function form_login($user_name, $autorization)
	{
		if ($user_name == 'Guest') 
		{
			
			echo <<<_FORM_LOGIN
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
_FORM_LOGIN;

		} 
		elseif ($autorization == TRUE)
		{
		echo <<<_FORM_EXIT
		<div class="login_form">
			<h4>Hello $user_name!</h4>
	    	<form name="sign_in" method="post">
	    		<input 
			    	type="submit"
			    	name="submit"  
			    	value="exit">
		    </form>
	    </div>
_FORM_EXIT;
		}
	    elseif ($autorization === FALSE)
		{
		echo <<<_FORM_LOGIN_FALSE
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
_FORM_LOGIN_FALSE;
		}
	}


	//лента коротких новостей
	public function short_news_list($user_access, $news_data)
	{
		echo '<div name="short_news_list" 
			class="col-md-6 short_news_list">';
		foreach ($news_data as $row_num => $row){
			$this->short_news($user_access, $row);
		}
		echo '</div>';

		

	}

	//короткая новость
	public function short_news($user_access, $news_data)
	{
######################		
		// echo '<pre> user data';
		// print_r($user_data);
		// echo '</pre>';

		echo '<div class="short_news_block">';
		if ($user_access == 'full') {
	      	$this->button_edit($news_data['id']);
		}

		$file = str_replace(__DIR__, '',__FILE__) ."?news_id=". $news_data['id'] .
			"&submit=full_news";
		echo <<<_SHORT_NEWS
					<a href="$file">
		        		<h4 name="news_header" class="news_name">{$news_data['header']}</h4>
		        		<p class="news_date">{$news_data['date']}</p>
		        		<p class="news_description">{$news_data['description']}</p>     
		     		</a>
    			</div>
_SHORT_NEWS;
	}
	
	//кнопка для редактирования новости
	public function button_edit($news_id)
	{
			echo <<<_BUTTON_EDIT
				    <form method="post" class="button_edit">
	        			<input type="hidden" name="news_id" value="$news_id">
	        			<input type="submit" name="submit" value="edit">
	      			</form>
_BUTTON_EDIT;
	}

	public function full_news()
	{
		
	}




	public function button_add_news($value='')
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

