<?php
	$host = explode('?', $_SERVER['REQUEST_URI'])[0];
	$num = substr_count($host, '/');
	$path = explode('/',$host)[$num];

	//index
	if($num == 2 and ($path == "" or $path == "index" or $path == "index.php")){
		$response = Controller::startSite();
	}
		
	//items
	elseif($path == 'items'){
		if(isset($_GET['category'])) $response = Controller::getItemsByCategory($_GET['category']);
		elseif(isset($_GET['id'])) $response = Controller::getItemById($_GET['id']);
		else $response = Controller::getAllItems();
	}
	
		

	//serials
	elseif($path == 'serials'){
		if(isset($_GET['category'])) $response = Controller::getSerialsByCategory($_GET['category']);
		elseif(isset($_GET['id']) and isset($_GET['season']) and isset($_GET['seria'])) $response = Controller::getSeria($_GET['id'], $_GET['season'], $_GET['seria']);
		elseif(isset($_GET['id']) and isset($_GET['season'])) $response = Controller::getSeriasBySerialSeason($_GET['id'], $_GET['season']);
		elseif(isset($_GET['id'])) $response = Controller::getSeasonsBySerialId($_GET['id']);
		else $response = Controller::getAllSerials();
	}


	//films
	elseif($path == 'films'){
		if(isset($_GET['category'])) $response = Controller::getFilmsByCategory($_GET['category']);
		elseif(isset($_GET['id'])) $response = Controller::getFilmById($_GET['id']);
		else $response = Controller::getAllFilms();
	}
	

	//registration&entring
	elseif($path == 'registration'){
		$response = Controller::registration();
	}
	elseif ($path == 'registrationAnswer') {
		if(isset($_POST['submit']))
			$response = Controller::registrationAnswer();
		else
			header("Location: registration");
	}
	elseif($path == 'enter'){
		$response = Controller::enter();
	}
	elseif($path == 'enterAnswer'){
		if(isset($_POST['submit']))
			$response = Controller::enterAnswer($_POST['email'], $_POST['password']);
		else
			header("Location: enter");
	}
	elseif($path == 'logout'){
		unset($_SESSION['user']);
		unset($_SESSION['favorites__item']);
		unset($_SESSION['favorites__season']);
		unset($_SESSION['favorites__seria']);
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}
	
	//favorites item
	elseif($path == "addFavoriteItem"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::addFavorite__item($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
		
	}
	elseif($path == "deleteFavoriteItem"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::deleteFavorite__item($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//favorite season
	elseif($path == "addFavoriteSeason"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::addFavorite__season($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
		
	}
	elseif($path == "deleteFavoriteSeason"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::deleteFavorite__season($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//favorite seria
	elseif($path == "addFavoriteSeria"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::addFavorite__seria($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
		
	}
	elseif($path == "deleteFavoriteSeria"){
		if(isset($_POST['id']) and isset($_SESSION['user'])){
			Controller::deleteFavorite__seria($_POST['id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//insert comments
	elseif($path == "insertCommentItem"){
		if(isset($_POST['id']) and isset($_POST['comment_text']) and isset($_SESSION['user'])){
			Controller::insertComment__item($_POST['id'], $_POST['comment_text']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}
	elseif($path == "insertCommentSeria"){
		if(isset($_POST['id']) and isset($_POST['comment_text']) and isset($_SESSION['user'])){
			Controller::insertComment__seria($_POST['id'], $_POST['comment_text']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//hideComment
	elseif($path == "hideCommentItem"){
		if(isset($_POST['comment_id']) and isset($_SESSION['user'])){
			Controller::hideComment__item($_POST['comment_id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}
	elseif($path == "hideCommentSeria"){
		if(isset($_POST['comment_id']) and isset($_SESSION['user'])){
			Controller::hideComment__seria($_POST['comment_id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//viewComment
	elseif($path == "viewCommentItem"){
		if(isset($_POST['comment_id']) and isset($_SESSION['user'])){
			Controller::viewComment__item($_POST['comment_id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}
	elseif($path == "viewCommentSeria"){
		if(isset($_POST['comment_id']) and isset($_SESSION['user'])){
			Controller::viewComment__seria($_POST['comment_id']);
		}
		$link = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"./";
		header("Location: {$link}");
	}

	//error
	else{
		$response = Controller::error404($_SERVER['REQUEST_URI']);
	}
?>