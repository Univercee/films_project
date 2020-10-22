

<?php
$item_in_row = 5;
class View{     
    //------------------------------------------------------------------------------------------------------------------------- VIEW ITEMS
    //-------------------------------------------------------------------- all items
    public static function viewItems($database_response_films, $database_response_serials, $categories){
        global $item_in_row;

        //view films
        if(!is_null($database_response_films)){
           View::viewFilms($database_response_films, $categories);
        }
        if(!is_null($database_response_serials)){
            View::viewSerials($database_response_serials, $categories);
        }
    }

    //-------------------------------------------------------------------- film list
    public static function viewFilms($database_response_films, $categories){
        global $item_in_row;
        $count_items = 0;
        //view title
        echo "
            <div class=\"container\">
                <h2 class=\"align-self-start\">Фильмы</h2> 
                <div class=\"row\">
            ";  
                foreach ($database_response_films as $item){  
                    View::viewItemInGrid($item['type'], $item['id'], $item['image'], $item['title'], $item['year'], $categories[$item['category_id']-1]['name']);
                    $count_items++;
                    $count_items%=$item_in_row;
                }
                for ($count_items; $count_items < $item_in_row; $count_items++) { 
                    echo "<div class=\"col\"></div>";
                }
        echo "
                </div>
            </div>";
    }

    //-------------------------------------------------------------------- serial list
    public static function viewSerials($database_response_serials, $categories){
        global $item_in_row;
        $count_items = 0;
        //view title
        echo "
            <div class=\"container\">
                <h2 class=\"align-self-start\">Сериалы</h2> 
                <div class=\"row\">
            ";        
                foreach ($database_response_serials as $item){  
                    View::viewItemInGrid($item['type'], $item['id'], $item['image'], $item['title'], $item['year'], $categories[$item['category_id']-1]['name']);
                    $count_items++;
                    $count_items%=$item_in_row;
                }
        echo "
                </div>
            </div>";
    }
    //-------------------------------------------------------------------- view item
    public static function viewItemInGrid($type, $id, $image, $title, $year, $category){
        echo "
        <div class=\"col-md-2 flex-column mb-5\" style=\"min-width:160px\">
            <a class=\"d-flex flex-wrap scrollLock--static\" href=\"{$type}?id={$id}\">
                <img class=\"content__item-img\" src=\"images/{$image}\">    
            </a>
            ";
        View::favoriteStar($id, $type);
        echo "  
            <a class=\"d-flex flex-wrap color-4 p-0 m-0 scrollLock\" href=\"serials?id={$id}\">
                <p class=\"color-4 p-0 m-0\">{$title}</p>
                <p class=\"color-3 p-0 m-0\">{$year}, {$category}</p>
            </a>
        </div>
        ";
    }






    //------------------------------------------------------------------------------------------------------------------------- VIEW FILTER
    //-------------------------------------------------------------------- filter
    public static function viewFilter($database_response){
        $host = explode('?', $_SERVER['REQUEST_URI'])[0];
        $num = substr_count($host, '/');
        $path = explode('/',$host)[$num];

        //set link type
        if($num == 2 and ($path == "" or $path == "index" or $path == "index.php")){
            $path = "items";
        }

        //filter header
        echo "
            <div class=\"filter d-flex flex-column justify-content-center mb-5\">
                <ul class=\"filter__title-list d-flex justify-content-center\">
                    <a href=\"items\"><li class=\"filter__list-item\">Все</li></a>
                    <a href=\"films\"><li class=\"filter__list-item\">Фильмы</li></a>
                    <a href=\"serials\"><li class=\"filter__list-item\">Сериалы</li></a>
                </ul>
            <div class=\"filter__grid justify-content-center verical-align-middle\">
            ";

        //filter body
        foreach($database_response as $value){
            //hovered categories icons
                //<div class=\"filter__grid-img filter__grid-img--main\" icon=\"{$value['icon']}\" style=\"background-image:url({$value['icon']})\"alt=\"\"></div>
                //<div class=\"filter__grid-img filter__grid-img--focused\" style=\"background-image:url({$value['icon_focused']})\"alt=\"\"></div>
            echo "
                <div class=\"filter__grid-cell row justify-content-center align-items-center px-4 py-3\">
                    <a href={$path}?category={$value['id']} class=\"filter__grid-text color-4 m-0\">{$value['name']}</a>
                </div>
                ";
        }
        echo "
                </div>
            </div>
            ";
    }





    //------------------------------------------------------------------------------------------------------------------------- VIEW ITEMS INNER CONTENT
    //-------------------------------------------------------------------- film
    public static function viewFilm($database_response){
        $last_url = isset($_SESSION['prev_url'])?$_SESSION['prev_url']:"./";
        echo    "<div class=\"d-flex justify-content-center my-5\">";
        echo        "<div class=\"mr-5\">";
        echo            "<img class=\"\" style=\"width:400px; height:600px;\" src=\"images/{$database_response['image']}\" alt=\"\">";
        echo        "</div>";
        echo        "<div class=\"d-flex flex-column align-self-center\">";
        echo            "<h1 class=\"\">{$database_response['title']}</h1>";
        echo            "<div class=\"flex-column\">";
                        for($i=0; $i<$database_response['rating'];$i++){
                            echo "<img src=\"images/other/star.png\" style=\"height:20px; width:20px;\">";
                        }
                        for($i=$database_response['rating']; $i<10;$i++){
                            echo "<img src=\"images/other/starEmpty.png\" style=\"height:20px; width:20px;\">";
                        }
        echo            "</div>";
        echo            "<p class=\"mt-4\">{$database_response['year']}</p>";
        echo            "<p class=\"mt-4\">{$database_response['description']}</p>";
        echo            "<a class=\"\" href=\"{$last_url}\">Назад</a>";
        echo        "</div>";
        echo    "</div>";
        echo    "<iframe class=\"video-player\" src=\"{$database_response['source']}\"></iframe>";
    }

    //-------------------------------------------------------------------- seasons
    public static function viewSeasons($database_response){
        $last_url = isset($_SESSION['prev_url'])?$_SESSION['prev_url']:"./";

        //view title
        echo "
            <h2 class=\"align-self-start\">Сезоны</h2> 
            <div class=\"content__item row\">
            ";        

        foreach ($database_response as $season){  
            //view film item
            echo "
                <a href=\"serials?id={$season['serial_id']}&season={$season['id']}\" class=\"col\">
                    <img class=\"content__item-img\" src=\"images/{$season['image']}\">
                    <p class=\"color-4 p-0 m-0\">Сезон {$season['number']}</p>
                </a>
                ";
        }
        echo "</div>";
        echo "<a class=\"content__item-more-btn\" href=\"{$last_url}\">Назад</a>";
    }
    //-------------------------------------------------------------------- serias
    public static function viewSerias($database_response){
        echo "<div class=\"content__item grid\">";
        for($i=0;$i<count($database_response);$i++){      
            $seria_title = $database_response[$i]['title']!=null ? $database_response[$i]['title']:"Серия {$database_response[$i]['number']}";  
            echo    "<a href=\"{$_SERVER['REQUEST_URI']}&seria={$database_response[$i]['number']}\" class=\"grid-cell\">";
            echo         "<img class=\"content__item-img\" src=\"images/{$database_response[$i]['image']}\">";
            echo         "<h2 class=\"content__item-title\">{$seria_title}</h2>";
            echo    "</a>";
        }
        echo "<a class=\"content__item-more-btn\" href=\"serials?id={$_GET['id']}\">К списку сезонов</a>";
    }
    //-------------------------------------------------------------------- seria
    public static function viewSeria($database_response){
        $last_url = isset($_SESSION['prev_url'])?$_SESSION['prev_url']:"./";
        echo    "<div class=\"d-flex justify-content-center my-5\">";
        echo        "<div class=\"mr-5\">";
        echo            "<img class=\"\" style=\"width:400px; height:600px;\" src=\"images/{$database_response['image']}\" alt=\"\">";
        echo        "</div>";
        echo        "<div class=\"d-flex flex-column align-self-center\">";
        echo            "<h1 class=\"\">{$database_response['title']}</h1>";
        echo            "<div class=\"flex-column\">";
                        for($i=0; $i<$database_response['rating'];$i++){
                            echo "<img src=\"images/other/star.png\" style=\"height:20px; width:20px;\">";
                        }
                        for($i=$database_response['rating']; $i<10;$i++){
                            echo "<img src=\"images/other/starEmpty.png\" style=\"height:20px; width:20px;\">";
                        }
        echo            "</div>";
        echo            "<p class=\"mt-4\">{$database_response['description']}</p>";
        echo            "<a class=\"\" href=\"{$last_url}\">Назад</a>";
        echo        "</div>";
        echo    "</div>";
        echo    "<iframe class=\"video-player\" src=\"{$database_response['source']}\"></iframe>";
    }

    //------------------------------------------------------------------------------------------------------------------------- VIEW USER DATA
    //-------------------------------------------------------------------- in header enter/registration
    public static function viewHeaderEnter(){
        if(isset($_SESSION['user']) and !is_null($_SESSION['user'])){
            echo "
            <div class=\"registration row align-items-center\">
                {$_SESSION['user']['name']}
                <a href=\"logout\">Выйти</a>
            </div>
            ";
        }
        else{
            echo "
            <div class=\"registration row align-items-center\">
                <a href=\"enter\">Войти </a>
                &nbsp;/&nbsp;
                <a href=\"registration\"> Зарегистрироваться</a>
            </div>
            ";
        }
    }


    //is favorite star
    public static function favoriteStar($id, $type){
        $favoriteItem = array(
            'item_id' => $id,
            'item_type' => $type
        );
        if(isset($_SESSION['favorites']) and in_array($favoriteItem, $_SESSION['favorites'])){
            echo "
                <form role=\"form\" method=\"POST\" action=\"deleteFavorite\">
                    <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                    <input type=\"hidden\" name=\"type\" value=\"{$type}\">
                    <input type=\"image\" src=\"images/other/star.png\" name=\"submit\" class=\"favorite-star favorite-star--fill scrollLock\">
                </form>
                ";
        }
        else{
            echo "
                <form role=\"form\" method=\"POST\" action=\"addFavorite\">
                    <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                    <input type=\"hidden\" name=\"type\" value=\"{$type}\">
                    <input type=\"image\" src=\"images/other/starEmpty.png\" name=\"submit\" class=\"favorite-star favorite-star--empty scrollLock\">
                </form>
                ";
        }
    }
}
?>

