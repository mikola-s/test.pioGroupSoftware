<?php
include_once('StartSet.php');
session_start();

$user = new user_class;
$html_part = new page_elements;
$news = new news_class;

$html_part->head(); //шапка стриницы

		echo '<pre>';
		echo 'session<br>';
		print_r ($_SESSION);
		echo 'post<br>';
		print_r ($_POST);
		echo '</pre><br>';


//главная станица после авторизации
if (!empty($_SESSION) && isset($_SESSION['login']) && (!empty($_POST))) {
	$user->login = $_SESSION['login'];
	$user->access = $_SESSION['access'];

	if (!empty($_POST) && (isset($_POST['form']))) {
		$html_part->form_login($user->login, TRUE);


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
				$full_news_data = $news->full_news($_POST['news_id']);
				$html_part->full_news($user->login, $user->access, $full_news_data);
				break;
			case 'edit_news':
				$full_news_data = $news->full_news($_POST['news_id']);
				$html_part->full_news_edit($user->login, $user->access, $full_news_data);
				break;
			case 'save_news':
				$news->save($_POST);
	 			$html_part->short_news_list($user->login, $user->access, $news->short_news());
				break;
			case 'cancel':
				# code...
				break;
				
			case 'add_news':
				$html_part->add($user->login, $user->access);
				break;
			case 'edit_user':
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


} elseif (empty($_SESSION) || ($_SESSION['login'] == 'Guest')) {
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

//-------------------------------------
	//обновление параметров сессии
	public function update_session()
	{
		$_SESSION['login'] = $this->login;
		$_SESSION['access'] = $this->access;
	}


	//добавить пользователя 
	public function add($user_login, $user_access){

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
		
		if ($post_data['id'] <> 0) {
			$query = <<<_QUERY_UPDATE
				UPDATE news SET 
				header = "{$post_data['header']}",
				description = "{$post_data['description']}",
				full_news = "{$post_data['full_news']}",
				date = "{$post_data['date']}" 
				WHERE news.id="{$post_data['id']}"
_QUERY_UPDATE;
			} else {
				$query = <<<_QUERY_ADD
				INSERT INTO news (id, header, description, full_news, date) 
				VALUES (NULL, "{$post_data['header']}", "{$post_data['description']}", 
				"{$post_data['full_news']}", "{$post_data['date']}")
_QUERY_ADD;
			}
		$mysqli_rezult = $mysqli->query($query);
		if (!$mysqli_rezult) {
   			exit($mysqli->error);
		}
		$mysqli->close();
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
				    
			    	<button 
			    		type="submit"
					    name="form"  
					    value="login">Login</button>
			    </form>
		    </div>
_FORM_LOGIN;
		} 
		elseif (($user_name != 'Guest') && ($autorization == TRUE)) {
		echo <<<_FORM_EXIT
		<div class="login_form">
			<h4>Hello $user_name!</h4>
	    	<form name="sign_in" method="post">
	    	<button 
	    		type="submit"
			    name="form"  
			    value="exit">Exit</button>
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
			    	<button 
			    		type="submit"
					    name="form"  
					    value="login">Login</button>
		    		<input 
						type="hidden" 
						form="form_{$news_data['id']}" 
						name="access" 
						value="$user_access">
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
	      	$this->buttons_edit_delete($user_login, $user_access, $news_data);
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
		    <p class="text_news">{$full_news_data['full_news']}</p>
   		</div>
_FULL_NEWS;
	}

#-------------------------------------------------------
	public function full_news_edit($user_login, $user_access, $full_news_data)
	{
		echo <<<_FULL_NEWS_EDIT
		    <form class="col-md-7 full_news_edit" method="post">
		    	<p>news date</p>
	    		<input 
	    			type="date" 
	    			class="area_edit_news col-md-3" 
	    			value="{$full_news_data['date']}" 
	    			name="date" 
	    			rows="1" 
	    			required>
		    	<br>
		    	<p>news header</p>
		    	<textarea 
		    		class="area_edit_news col-md-7" 
		    		name="header" 
		    		rows="1" 
		    		required
		    		>{$full_news_data['header']}</textarea>
		    	<br>
		    	<p>news description</p>
		    	<textarea 
		    		class="area_edit_news col-md-7" 
		    		name="description" 
		    		rows="2"
		    		required
		    		>{$full_news_data['description']}</textarea>
		    	<br>
		    	<p>news full_news</p>
		    	<textarea
		    		class="area_edit_news col-md-7" 
		    		name="full_news" 
		    		rows="20"
		    		required
		    		>{$full_news_data['full_news']}</textarea>

		    	<button 
		    		type="submit"
				    name="form"  
		    		value="save_news">save</button>

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
### сюда кнопку отмена 
### 
		
	}
#-------------------------------------------------------
	public function add($user_login, $user_access)
	{
		$empty_news_data = array( 
			'id' => '0',
			'header' => '',
			'description' => '',
			'full_news' => '',
			'date' => '');	
		$this->full_news_edit($user_login, $user_access, $empty_news_data);
	}

#-------------------------------------------------------
	public function buttons_edit_delete($user_login, $user_access, $news_data)
	{
		echo '<div class="buttons_edit_delete">';
		$this->button_news_edit($user_login, $user_access, $news_data);
		$this->button_news_delete($user_login, $user_access, $news_data);
		echo "</div>";
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

		    	<button 
				    name="form"  
				    value="edit_news">Edit</button>

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

#####################################
#-------------------------------------------------------
	//кнопка для редактирования новости
	public function button_news_delete($user_login, $user_access, $news_data)
	{
			echo <<<_BUTTON_DELETE
		    <form method="post" class="button_news_edit">
    			<input 
    				type="hidden" 
    				name="news_id" 
    				value="{$news_data['id']}">
		    	<button 
		    		type="submit"
				    name="form"  
				    value="delete_news">delete</button>
    			<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
      			</form>
_BUTTON_DELETE;
	}

#####################################
public function button_news_cancel($user_login, $user_access, $news_data)
	{
			echo <<<_BUTTON_CANCEL
		    <form method="post" class="button_news_edit">
    			<input 
    				type="hidden" 
    				name="news_id" 
    				value="{$news_data['id']}">
		    	<button 
		    		type="submit"
				    name="form"  
    				value="cancel_news">Cencel</button>
    			<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
      			</form>
_BUTTON_CANCEL;
	}

#####################################
public function button_user_list($user_login, $user_access, $news_data)
	{
			echo <<<_BUTTON_USER_LIST
		    <form method="post" class="button_news_edit">
    			<input 
    				type="hidden" 
    				name="news_id" 
    				value="{$news_data['id']}">
		    	<button 
		    		type="submit"
				    name="form"  
				    value="user_list">User list</button>
    			<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
      			</form>
_BUTTON_USER_LIST;
	}

#####################################
public function button_home($user_login, $user_access, $news_data)
	{
			echo <<<_BUTTON_CANCEL_NEWS
		    <form method="post" class="button_news_edit">
    			<input 
    				type="hidden" 
    				name="news_id" 
    				value="{$news_data['id']}">
		    	<button 
		    		type="submit"
				    name="form"  
				    value="cancel_news">cancel</button>
    			<input 
					type="hidden" 
					name="login" 
					value="$user_login">
				<input 
					type="hidden" 
					name="access" 
					value="$user_access">
      			</form>
_BUTTON_CANCEL_NEWS;
	}


#-------------------------------------------------------
	public function button_add_news($user_login, $user_access)
	{
		echo <<<_BUTTON_ADD_NEWS
		<form method="post" class="form_button_add_news">
	    	<button 
      			class="button_add_news" 
	    		type="submit"
			    name="form"  
      			value="add_news">add news</button>
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

