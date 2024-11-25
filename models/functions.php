<?php 
define("ADMIN_ROW_COUNT", 5);
function getAll($query){
    global $connection;
    return $connection->query($query)->fetchAll();
}
function getOne($query){
    global $connection;
    return $connection->query($query)->fetch();
}

function softDelete($table, $status, $id){
    global $connection;
    $status = (int)$status === 0 ? 1 : 0;
    $query = "update $table set is_deleted = ? where id = ?";
    $update = $connection->prepare($query);
    $update->execute([$status, $id]);
}

function deleteFromTable($table, $column, $value){
    global $connection;
    $query = "delete from $table where $column = ?";
    $delete = $connection->prepare($query);
    $delete->execute([$value]);
}



$returnAfterDelete = fn($table, $id) => getOne("select id, is_deleted from $table where id = '$id'");

function printErrors($errorArray){
    foreach($errorArray as $error){
        echo json_encode($error);
        http_response_code(422);    
    }
}
// links 
function getAllLinks($limit = 0){
    $query = "select * from links";
    $limit = (int)$limit * (int)ADMIN_ROW_COUNT;
    $offset = (int)ADMIN_ROW_COUNT;
    $query.= " limit $limit, $offset";
    return getAll($query);
}

function linkPagination(){
    $query = "select count(*) as links from links";
    $res = getOne($query);
    $numberOfPages = ceil($res->links/(int)ADMIN_ROW_COUNT);
    return $numberOfPages;
}


$getOneLink = fn($linkId) => getOne("select id, name from links where id = '$linkId'");
$checkLink = fn($name) => getOne("select id, name from links where name = '$name'");
$getOneLinkFullRow = fn($linkId) => getOne("select * from links where id ='$linkId'");

function insertNewLink($name){
    global $connection;
    $query = "insert into links (name) values(?)";
    $insert = $connection->prepare($query);
    $insert->execute([$name]);
}
function updateLink($name, $id){
    global $connection;
    $date = date("Y-m-d H:i:s");
    $query = "update links set name = ?, updated_at =? where id =?";
    $update = $connection->prepare($query);
    $update->execute([$name, $date, $id]);
}

// platforms

function getAllPlatforms($limit = 0){
    $query = "select * from platforms";
    $offset = (int)ADMIN_ROW_COUNT;
    $limit = (int)$limit * $offset;
    $query.= " limit $limit, $offset";
    return getAll($query);
}
function platformPagination(){
    $query = "select count(*) as numberOfElements from platforms";
    $data = getOne($query);
    return ceil($data->numberOfElements / (int) ADMIN_ROW_COUNT);
}

$getOnePlatform = fn($platformId) => getOne("select id, name from platforms where id = '$platformId'");
$checkPlatformName = fn($name) => getOne("select id, name from platforms where name = '$name'");
$getOnePlatformFullRow = fn($platformId) => getOne("select * from platforms where id = '$platformId'");
function insertNewPlatform($name){
    global $connection;
    $query = "insert into platforms (name) values (?)";
    $insert = $connection->prepare($query);
    $insert->execute([$name]);
}
function updatePlatform($name, $id){
    global $connection;
    $date = date("Y-m-d H:i:s");
    $query = "update platforms set name =?, updated_at =? where id = ?";
    $update = $connection->prepare($query);
    $update->execute([$name, $date, $id]);
}

// pubilshers

function getAllPublishers($limit = 0){
    $query = "select * from publishers";
    $offset = (int)ADMIN_ROW_COUNT;
    $limit = (int)$limit * $offset;
    $query.= " limit $limit, $offset";
    return getAll($query);
}

function publisherPagination(){
    $query = "select count(*) as numberOfRows from publishers";
    $data = getOne($query);
    $numberOfPages = ceil($data->numberOfRows / (int) ADMIN_ROW_COUNT);
    return $numberOfPages;
}

$getOnePublisher = fn($publisherId) => getOne("select id, name from publishers where id = '$publisherId'");
$checkPublisherName = fn($name) => getOne("select id, name from publishers where name = '$name'");
$getPublisherFullRow = fn($publisherId) => getOne("select * from publishers where id = '$publisherId'");
function insertNewPublisher($name){
    global $connection;
    $query = "insert into publishers (name) values(?)";
    $insert = $connection->prepare($query);
    $insert->execute([$name]);
}
function updatePublisher($name, $id){
    global $connection;
    $query = "update publishers set name =?, updated_at = ? where id = ?";
    $date = date("Y-m-d H:i:s");
    $update = $connection->prepare($query);
    $update->execute([$name, $date, $id]);
}

// auth
$checkMail = fn($email) => getOne("select id from users where email = '$email'");

function storeNewAccount($first_name, $last_name, $email, $password){
    global $connection;
    define("USER_ROLE", 2);
    $query = "insert into users (first_name, last_name, email, password, role_id) values(?,?,?,?,?)";
    $insert = $connection->prepare($query);
    $insert->execute([$first_name, $last_name, $email, md5($password), (int)USER_ROLE]);
}
function checkAccountByEmailAndPassword($email, $password){
    global $connection;
    $query = "select id, role_id from users where email = ? and password = ?";
    $select = $connection->prepare($query);
    $select->execute([$email, md5($password)]);
    return $select->fetch();
}

function redirectPage(){
  $page =   isset($_SESSION['user']) && $_SESSION['user']->role_id === 1 ? "admin.php" : "index.php";
  return $page;
}

function navFunction(){
    $table = !isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->role_id === 2) ? 'links' : 'admin_links';
    $page  = !isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->role_id === 2) ? 'index.php' : 'admin.php';
    $query = "select * from $table";
    return [
        'pages' => getAll($query),
        'url' => $page
    ];
}


// games 
$getPegiRating = fn() => getAll("select * from pegi_rating");
function getAllGames($limit = 0){
    $query = "select g.*, p.name as publisherName, pg.number as pegiNumber from games g join publishers p on g.publisher_id = p.id
        join pegi_rating pg on g.pegi_id = pg.id";
    $offset = (int)ADMIN_ROW_COUNT;
    $limit = (int)$limit * $offset;
    $query.=" limit $limit, $offset";
    return getAll($query);
}
$getAvailablePublishers = fn() => getAll('select * from publishers where is_deleted = 0');
$getAllGenres = fn() => getAll("select * from genres");
$checkGameName = fn($name) => getOne("select id, name from games where name ='$name'");


function getOneGame($gameId){
    $query = "select id, name, description, publisher_id as publisher , trailer, pegi_id as pegi_rating , published_at from games where id = '$gameId'";
    $game = getOne($query);

    $game->genres = getGenres($game->id);
    return $game;
}

function getGenres($game_id){
    $arrayGenre = [];
    $genres = getAll("select genre_id from game_genre where game_id ='$game_id'");
    foreach($genres as $genre){
        $arrayGenre[] = $genre->genre_id;
    }
    return $arrayGenre;
}

function insertGame($name, $description, $publisher, $pegi_rating, $published_at, $genres, $trailer_url){
    global $connection;
    $connection->beginTransaction();
    $trailer_url = explode("=", $trailer_url)[1];
    $query = "insert into games (name, publisher_id, description, pegi_id, published_at,  trailer) values(?,?,?,?,?,?)";
    $insert = $connection->prepare($query);
    if($insert->execute([$name, $publisher, $description, $pegi_rating, $published_at, $trailer_url])){
        $last_id = $connection->lastInsertId();
        insertGameGenre($last_id, $genres);
        $connection->commit();
    }
    else{
        $connection->rollBack();
    }
}

function updateGame($id, $name, $description, $pegi_id, $published_at, $publisher, $genres, $trailer_url = ""){
    global $connection;
    $queryArrayValues = [];
    $connection->beginTransaction();
    $query = "update games set name = ?, publisher_id = ?, description = ?, pegi_id = ?, published_at = ?";
    array_push($queryArrayValues, $name);
    array_push($queryArrayValues, $publisher);
    array_push($queryArrayValues, $description);
    array_push($queryArrayValues, $pegi_id);
    array_push($queryArrayValues, $published_at);
    if($trailer_url !== ""){
        $query.= ", trailer_url = ?";
        array_push($queryArrayValues, $trailer_url);
    }

    $query.= ",updated_at = ? where id = ?";
    $date = date("Y-m-d H:i:s");
    array_push($queryArrayValues, $date);
    array_push($queryArrayValues, $id);
    $update = $connection->prepare($query);
    if($update->execute($queryArrayValues)){
        deleteFromTable('game_genre', 'game_id', $id);
        insertGameGenre($id, $genres);
        $connection->commit();
    }else{
        $connection->rollBack();
    }
}

$getGameFullRow = fn($game_id) => getOne("select g.id, g.name, g.published_at, g.created_at, g.updated_at, p.name as publisherName, pg.number as pegiName from games g join publishers p on g.publisher_id = p.id join pegi_rating pg on g.pegi_id = pg.id where g.id = '$game_id'");

function insertGameGenre($game_id, $genres){
    global $connection;
    $query = "insert into game_genre (game_id, genre_id) values";
    $queryParams = [];
    $queryValues = [];
    foreach($genres as $genre){
        $queryParams[] = "(?,?)";
        $queryValues[] = (int)$game_id;
        $queryValues[] = (int)$genre;
    }

    $query.= implode(",", $queryParams);
    $insert = $connection->prepare($query);
    $insert->execute($queryValues);
}

function gamePagination(){
    $query = "select count(*) as numberOfElements from games";
    $res = getOne($query);
    return ceil($res->numberOfElements / (int)ADMIN_ROW_COUNT);
}

// editions 
$getAvailablePlatforms = fn() => getAll("select id, name from platforms where is_deleted = 0");
function getAllEditions($game_id){
    $query = "
        select  ge.id,p.name as platformName,
        e.name as editionName, 
        pr.price,
        ge.created_at, ge.updated_at, ge.is_deleted
        from game_edition ge
        join platforms p on ge.platform_id = p.id
        join editions e on ge.edition_id = e.id
        join prices pr on p.id = pr.game_edition_id
        where ge.game_id = '$game_id'
        GROUP BY ge.id
            ";
    return getAll($query);
}

$getOneGameEdition = fn($editionId) => getOne("select ge.id, ge.platform_id as platforms ,ge.edition_id as edition, ge.image_name as cover, p.price from game_edition ge join prices p on ge.id = p.game_edition_id where ge.id = '$editionId'");
$checkGameEditionPlatform = fn($game_id, $platform_id, $edition_id) => getOne("select id, platform_id, game_id from game_edition where game_id = '$game_id' and platform_id = '$platform_id' and edition_id = '$edition_id'");
$getEditionTypes = fn() => getAll("select * from editions");
function uploadImage($image){
    $tmp_name = $image['tmp_name'];
    $name = $image['name'];
    $new_image_name = time()."_".$name;
    $addressTo = "../../assets/img/normal/$new_image_name";
    move_uploaded_file($tmp_name, $addressTo);
    resizeImage($addressTo, $new_image_name);

    return $new_image_name;

}

function resizeImage($loadImageFrom, $new_image_name){
    list($width, $height) = getimagesize($loadImageFrom);
    // calculated images
    $new_width = 200;
    $new_height  = $height / ($width / $new_width);
    $image_type = exif_imagetype($loadImageFrom);
    $resized_image = imagecreatetruecolor($new_width, $new_height);
    switch($image_type){
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($loadImageFrom);
            break;
        case IMAGETYPE_PNG :
            $source_image = imagecreatefrompng($loadImageFrom);
            break;
        default: 
            die("Unsupported type");
    }
    imagecopyresampled($resized_image, $source_image,0,0,0,0, $new_width, $new_height, $width, $height);
    $resized_image_path = "../../assets/img/small/$new_image_name";
    switch($image_type){
        case IMAGETYPE_JPEG:
            imagejpeg($resized_image, $resized_image_path);
            break;
        case IMAGETYPE_PNG:
            imagepng($resized_image, $resized_image_path);
            break;
    }
    
}

function removeOldImage($old_image_cover){
    $file_path_normal = "../../assets/img/normal/$old_image_cover";
    $file_path_small = "../../assets/img/small/$old_image_cover";

    if(file_exists($file_path_small) && file_exists($file_path_normal)){
        unlink($file_path_normal);
        unlink($file_path_small);
    }
}

function insertGameEdition($game_id, $platform_id, $edition_id, $image_name, $price){
    global $connection;
    $connection->beginTransaction();
    $query = "insert into game_edition (game_id, platform_id, edition_id, image_name) values(?,?,?,?)";
    $insert = $connection->prepare($query);
    if($insert->execute([$game_id, $platform_id, $edition_id, $image_name])){
        $last_id = $connection->lastInsertId();
        insertPrice($last_id, $price);
        $connection->commit();
    }else{
        $connection->rollBack();
    }
}

function insertPrice($game_edition_id, $price){
    global $connection;
    $queryInsert = "insert into prices (game_edition_id, price) values(?,?)";
    $insert = $connection->prepare($queryInsert);
    $insert->execute([$game_edition_id, $price]);
}


function updateGameEdition($id, $game_id, $platform_id, $edition_id,$price, $image_name = ""){
    global $connection;
    $connection->beginTransaction();
    $query = "update game_edition set  platform_id= ?, edition_id = ?";
    $queryParams = [];
    array_push($queryParams, $platform_id);
    array_push($queryParams, $edition_id);
    if($image_name = ""){
        $query.=", image_name = ?";
        array_push($queryParams, $image_name);
    }
    $query.= ", updated_at = ? where id = ?";
    array_push($queryParams, date("Y-m-d H:i:s"));
    array_push($queryParams, $id);
    $update = $connection->prepare($query);
    if($update->execute($queryParams)){
        updatePrice($edition_id, $price);
        $connection->commit();
    }else{
        $connection->rollBack();
    }
}
 
function updatePrice($edition_id, $price){
    global $connection;
    $query = "update prices set price =? where game_edition_id   = ?";
    $update = $connection->prepare($query);
    $update->execute([$price, $edition_id]);
}
function getGameEditionFullRow($edition_id ){
    $query = "select ge.created_at, ge.updated_at, ge.is_deleted ,
        p.name as platformName, e.name as editionName,
        pl.price
        from game_edition ge join platforms p
        on ge.platform_id = p.id 
        join editions e on ge.edition_id = e.id
        join prices pl on e.id = pl.game_edition_id
        where ge.id = '$edition_id'";
    return getOne($query);
}

// logger for unsuccessfull operation
function invalidOperationLogger($logger){

}