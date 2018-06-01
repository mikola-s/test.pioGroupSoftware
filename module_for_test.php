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
		echo '</pre>';
//главная станица после авторизации

if (!empty($_POST) && (isset($_POST['form']))) {

		switch ($_POST['form']) {
			case 'login':
				$login_from_post = htmlentities($_POST['login']);
				$password_from_post = htmlentities($_POST['password']);
				if ($user->autorization($login_from_post, $password_from_post)) {
					$html_part->form_login($login_from_post, TRUE);
				} 	
				else {
					$html_part->form_login($login_from_post, FALSE);
				}
	 			$html_part->short_news_list($user->login, $user->access, $news->short_news());
				break;
			case 'exit':
				$user->login = 'Guest';
				$user->access = 'read';
//-------------------------------------
				$user->update_session();
//-------------------------------------
				$html_part->form_login($user->login, TRUE);
	 			$html_part->short_news_list($user->login, $user->access, $news->short_news());
				break;
			case 'full_news':
				$user->login = $_POST['login'];
				$user->access = $_POST['access'];

				$html_part->form_login($user->login, TRUE);
				$full_news_data = $news->full_news($_POST['news_id']);
				$html_part->full_news($user->login, $user->access, $full_news_data);
				break;
			case 'edit':
				$user->login = $_POST['login'];
				$user->access = $_POST['access'];
				$html_part->form_login($user->login, TRUE);
				$full_news_data = $news->full_news($_POST['news_id']);
				$html_part->full_news_edit($user->login, $user->access, $full_news_data);

				break;
			case 'save_news':
				$user->login = $_POST['login'];
				$user->access = $_POST['access'];
				$news->save($_POST);
				$html_part->form_login($user->login, TRUE);
	 			$html_part->short_news_list($user->login, $user->access, $news->short_news());
				break;
			case 'value':
				# code...
				break;
			case 'value':
				# code...
				break;
			case 'value':
				# code...
				break;
			case 'value':
				# code...
				break;
			case 'value':
				# code...
				break;
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}
	} 

elseif (empty($_POST)) {
	$user->login = 'Guest';
	$user->access = 'read';
	$user->update_session();
	$html_part->head();
	$html_part->form_login($user->login, TRUE);
 	$html_part->short_news_list($user->login, $user->access, $news->short_news());

}

#######################
			// echo '<pre>';
			// echo 'session<br>';
			// print_r ($_SESSION);
			// echo '</pre>';
#######################


/**
 * 
 */
class user_class 
{
	public $password, $login, $access;
	#public $login = 'Guest';
	#public $access = 'read';

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
######################
// echo "<br><pre>user_data ";
// print_r($user_data);
// echo "</pre>";
#####################
//-------------------------------------
	//обновление параметров сессии
	public function update_session()
	{
		$_SESSION['login'] = $this->login;
		$_SESSION['access'] = $this->access;
	}


	//добавить пользователя 
	public function add(){

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
	public function full_news($news_id)
	{
		$mysqli = $this->mysql_open();
		$query = "SELECT * FROM news WHERE id=$news_id";
		if ($mysqli_rezult = $mysqli->query($query)) {
			$full_news_data = $mysqli_rezult->fetch_assoc();
		}
		$mysqli->close();
		return $full_news_data;
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

	public function save($post_data)
	{
		$mysqli = $this->mysql_open();
		$query = <<<_QUERY
			UPDATE news SET 
			header = "{$post_data['header']}",
			description = "{$post_data['description']}",
			full_news = "{$post_data['full_news']}",
			date = "{$post_data['date']}" 
			WHERE news.id="{$post_data['id']}"
_QUERY;
		$mysqli_rezult = $mysqli->query($query);
		if (!$mysqli_rezult) {
   			exit($mysqli->error);
		}
		$mysqli->close();
	}

	public function save_news($value='')
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

#-------------------------------------------------------
	public function form_login($user_name, $autorization)
	{
		if (($user_name == 'Guest') && ($autorization == TRUE)) {
			
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
				    	name="form"  
				    	value="login">
			    </form>
		    </div>
_FORM_LOGIN;
		} 
		elseif (($user_name != 'Guest') && ($autorization == TRUE)) {
		echo <<<_FORM_EXIT
		<div class="login_form">
			<h4>Hello $user_name!</h4>
	    	<form name="sign_in" method="post">
	    		<input 
			    	type="submit"
			    	name="form"  
			    	value="exit">
		    </form>
	    </div>
_FORM_EXIT;
		}
	    elseif (($user_name != 'Guest') && ($autorization == FALSE)) {
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
				    	name="form"  
				    	value="login">
			    </form>
		    </div>
_FORM_LOGIN_FALSE;
		}
	}

#-------------------------------------------------------
	//лента коротких новостей
	public function short_news_list($user_login, $user_access, $news_data)
	{
		
		echo '<div name="short_news_list" 
			class="col-md-6 short_news_list">';
		if ($user_access == 'full') {
			$this->button_add_news($user_login, $user_access);
		}
		foreach ($news_data as $query_row_num => $query_row){
			$this->short_news($user_login, $user_access, $query_row);
		}
		echo '</div>';
	}

#-------------------------------------------------------
	//короткая новость
	public function short_news($user_login, $user_access, $news_data)
	{
		echo <<<_SHORT_NEWS
		<div class="short_news_block" 
			onclick='document.getElementById("form_{$news_data['id']}").submit();'>
		<form 
			id="form_{$news_data['id']}" 
			method="post" 
			class="short_news_form">
			<input 
				type="hidden" 
				name="news_id" 
				value="{$news_data['id']}">
		</form>
_SHORT_NEWS;

		if ($user_access == 'full') {
	      	$this->button_news_edit($user_login, $user_access, $news_data);
		}

		echo <<<_SHORT_NEWS_2
		<input 
			type="hidden" 
			form="form_{$news_data['id']}" 
			name="form" 
			value="full_news">
		<input 
			type="hidden" 
			form="form_{$news_data['id']}" 
			name="login" 
			value="$user_login">
		<input 
			type="hidden" 
			form="form_{$news_data['id']}" 
			name="access" 
			value="$user_access">
    	<h4 name="news_header" 
    		class="news_name">{$news_data['header']}</h4>
    	<p class="news_date">{$news_data['date']}</p>
    	<p class="news_description">{$news_data['description']}</p>
	</div>
_SHORT_NEWS_2;
	}
    			
#-------------------------------------------------------
	//кнопка для редактирования новости
	public function button_news_edit($user_login, $user_access, $news_data)
	{
			echo <<<_BUTTON_EDIT
		    <form method="post" class="button_news_edit">
    			<input 
    				type="hidden" 
    				name="news_id" 
    				value="{$news_data['id']}">
    			<input 
    				type="submit" 
    				name="form" 
    				value="edit">
    			<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
      			</form>
_BUTTON_EDIT;
	}

#-------------------------------------------------------
	public function full_news($user_login, $user_access, $full_news_data)
	{
		
		echo '<div name="full_text_news" class="col-md-7 full_text_news">';
		if ($user_access == 'full') {
	      	$this->button_news_edit($user_login, $user_access, $full_news_data);
		}
		echo <<<_FULL_NEWS
		    <h4 name="news_header" 
    		class="news_name">{$full_news_data['header']}</h4>
    		<p class="news_date">{$full_news_data['date']}</p>
    		<p class="news_description">{$full_news_data['description']}</p>
		    <hr>
		    <div class="full_text_news">{$full_news_data['full_news']}</div>
   		</div>
_FULL_NEWS;
	}

#-------------------------------------------------------
	public function full_news_edit($user_login, $user_access, $full_news_data)
	{
		
		echo <<<_FULL_NEWS_EDIT
		    <form class="col-md-7 full_news_edit" method="post">

		    	<p>news date</p>
		    		<input type="date" class="area_edit_news col-md-3" value="{$full_news_data['date']}" name="date" rows="1">
		    	<br>
		    	<p>news header</p>
		    	<textarea class="area_edit_news col-md-7" name="header" rows="1">{$full_news_data['header']}</textarea>
		    	<br>
		    	<p>news description</p>
		    	<textarea class="area_edit_news col-md-7" name="description" rows="2">{$full_news_data['description']}</textarea>
		    	<br>
		    	<p>news full_news</p>
		    	<textarea class="area_edit_news col-md-7" name="full_news" rows="20">{$full_news_data['full_news']}</textarea>
		    	<input 
		    		type="submit" 
		    		name="form" 
		    		value="save_news">
		    	<input 
					type="hidden" 
					name="id" 
					value="{$full_news_data['id']}">
		    	<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
    		</form>
_FULL_NEWS_EDIT;
	}

#-------------------------------------------------------
	
	public function button_add_news($user_login, $user_access)
	{
		echo <<<_BUTTON_ADD_NEWS
		<form method="post" class="form_button_add_news">
      		<input type="submit" class="button_add_news" name="form" value="add_news">
	    	<input 
				type="hidden" 
				name="login" 
				value="$user_login">
			<input 
				type="hidden" 
				name="access" 
				value="$user_access">      		
      	</form>
_BUTTON_ADD_NEWS;
	}

#-------------------------------------------------------
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

