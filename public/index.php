<?php

/*test : 
http://localhost/?page=games&id=369              Game ID
http://localhost/?page=games&title=batman        Game Title
http://localhost/?page=deals              Deals All
http://localhost/?page=deals&id=sz546Tl6B1DiwBR5j9oG50afCXd9qJ4x2nxDWDESmSI%3D        Deals ID


Ajouter l'apitoken à la fin pour l'auth :
    [...] &apitoken=ac2EYfyuQXIQdLmH3PlTxIZpxYWykXMuw6ZniKl2y19xRrvYC5o0bjPsXN2yCXlm
*/

$servername = "127.0.0.1";
$username = "root";
$password = "";
$bdd = "cheap-shark";

// Créer connection BDD
$conn = new mysqli($servername, $username, $password, $bdd);

// Check connection
if ($conn->connect_error) {
  die("Connection refusé: " . $conn->connect_error);
}

// Récupérer les paramètres de l'URL
$page = $_GET['page'];
$id = isset($_GET['id']) ? $_GET['id'] : null;
$title = isset($_GET['title']) ? $_GET['title'] : null;

// URL de l'API 
$api_url = "https://www.cheapshark.com/api/1.0/{$page}";

// Ajouter les paramètres à l'URL si présents
if ($id !== null) {
    $api_url .= "?id={$id}";
}
if ($page === 'games' && $title !== null) {
    $api_url .= "?title={$title}";
}



// Vérifier si le token est bon
$apiToken = $_GET['apitoken'];
$sqlToken = "SELECT * FROM users WHERE apiToken = ?";
$stmt = $conn->prepare($sqlToken);

if ($stmt === false) {
    die("Échec de la préparation de la requête : " . $conn->error);
}

// Binder les paramètres + executer
$stmt->bind_param("s", $apiToken);
$stmt->execute();
$resultatToken = $stmt->get_result();


// Test Token API
if (!isset($apiToken)) {
    echo "Veuillez renseigner votre token";

} else if ($resultatToken->num_rows > 0){     
    

// Faire la requête à l'API
$api_response = file_get_contents($api_url);

// Traiter la réponse 
$data = json_decode($api_response, true);

// Faire quelque chose avec les données récupérées
print_r($data);

echo "<br><br>";


// test avec l'url
if (strpos($_SERVER['REQUEST_URI'], "games")==true && strpos($_SERVER['REQUEST_URI'], "id")==true){
    
    gameByID($data, $conn);
    
} else if (strpos($_SERVER['REQUEST_URI'], "games")==true && strpos($_SERVER['REQUEST_URI'], "title")==true) {
    
    gameByTitle($data, $conn);
    
} else if (strpos($_SERVER['REQUEST_URI'], "deals")==true && strpos($_SERVER['REQUEST_URI'], "id")==true) {
    
    dealsByID($data, $conn);
} else if (strpos($_SERVER['REQUEST_URI'], "deals")==true) {
    
    dealsAll($data, $conn);

}

echo "<br><br>";

} else {
    echo 'Erreur 403 : Mauvais token';
}



function gameByID($data, $conn) {
    // Recupérer les données qui seront stocké en BDD
    $gameTitle = $data['info']['title'];
    $gameID = $_GET['id'];
    $search = '';
    

    $escapedGameTitle = mysqli_real_escape_string($conn, $gameTitle);
    $escapedGameID = $conn->real_escape_string($gameID);
    
    // BDD
    // Vérifier si le gameID existe déjà
    $checkExistingQuery = "SELECT * FROM `cheap-shark`.`games` WHERE `gameID` = '$gameID'";
    $existingResult = $conn->query($checkExistingQuery);
    
    if ($existingResult->num_rows == 0) {
        
    $sql = "INSERT INTO `cheap-shark`.`games`
    VALUES ('$escapedGameTitle', $escapedGameID, '$search');";

if ($conn->query($sql) === TRUE) {
    echo "$escapedGameTitle ajouté à la BDD";
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    }
    
    $conn->close();
}

function gameByTitle($data, $conn) {
    $external = array();
    $gameID = array();
    
    $search = $_GET['title'];
    
    echo 'recherche : ' . $search . '<br>';

    foreach ($data as $game) {
        // Récupérer les noms external
        $external[] = $game['external'];
        
        // Récupérer les game ID
        $gameID[] = $game['gameID'];
        
        
        $escapedGameTitle = $conn->real_escape_string($game['external']);
        $escapedGameID = $conn->real_escape_string($game['gameID']);
        
        // Vérifier si le gameID existe déjà
        $checkExistingQuery = "SELECT * FROM `cheap-shark`.`games` WHERE `gameID` = '$escapedGameID'";
        $existingResult = $conn->query($checkExistingQuery);
        
        if ($existingResult->num_rows == 0) {
            $sql = "INSERT INTO `cheap-shark`.`games`
                            VALUES ('$escapedGameTitle', '$escapedGameID', '$search');";
            
            
            
            if ($conn->query($sql) === TRUE) {
                echo "$escapedGameTitle ajoutés à la BDD<br>";
            } else {
                echo "Erreur: " . $sql . "<br>" . $conn->error . "<br>";
            }
        }
    }
    // Afficher les résultats
    // echo "jeux :";
    // echo "<br>";
    // echo "external : " . implode(', ', $external);
    // echo "<br>";
    // echo "game IDs : " . implode(', ', $gameID);
    // echo "<br>";
}

function dealsByID($data, $conn) {
    // Recupérer les données qui seront stocké en BDD
    $dealTitle = $data['gameInfo']['name'];
    $dealID = $_GET['id'];
    $dealSalePrice = $data['gameInfo']['salePrice'];
    $dealNormalPrice = $data['gameInfo']['retailPrice'];

    
    $escapedDealTitle = mysqli_real_escape_string($conn, $dealTitle);
    $escapedDealSalePrice = mysqli_real_escape_string($conn, $dealSalePrice);
    $escapedDealNormalPrice = mysqli_real_escape_string($conn, $dealNormalPrice);
    
    
    // BDD
    // Vérifier si le dealID existe déjà
    $checkExistingQuery = "SELECT * FROM `cheap-shark`.`deals` WHERE `dealID` = '$dealID'";
    $existingResult = $conn->query($checkExistingQuery);
    
    if ($existingResult->num_rows == 0) {
        $insertQuery = "INSERT INTO `cheap-shark`.`deals` VALUES ('$dealID', '$escapedDealTitle', '$escapedDealSalePrice', '$escapedDealNormalPrice');";

    
    if ($conn->query($insertQuery) === TRUE) {
        echo "$escapedDealTitle ajouté à la BDD";
            } else {
                echo "Erreur: " . $insertQuery . "<br>" . $conn->error;
            }
        }
        
        $conn->close();
}

function dealsAll($data, $conn) {
    $title = array();
    $dealID = array();
    $salePrice = array();
    $normalPrice = array();

    foreach ($data as $deal) {
        $title[] = $deal['title'];
        $dealID[] = $deal['dealID'];
        $salePrice[] = $deal['salePrice'];
        $normalPrice[] = $deal['normalPrice'];
        
        
        $escapedDealTitle = $conn->real_escape_string($deal['title']);
        $escapedDealID = $conn->real_escape_string($deal['dealID']);
        $escapedSalePrice = $conn->real_escape_string($deal['salePrice']);
        $escapedNormalPrice = $conn->real_escape_string($deal['normalPrice']);
        
        // Vérifier si le dealsID existe déjà
        $checkExistingQuery = "SELECT * FROM `cheap-shark`.`deals` WHERE `dealID` = '$escapedDealID'";
        $existingResult = $conn->query($checkExistingQuery);
        
        if ($existingResult->num_rows == 0) {
            $insertQuery = "INSERT INTO `cheap-shark`.`deals`
                            VALUES ('$escapedDealID', '$escapedDealTitle', '$escapedSalePrice', '$escapedNormalPrice');";
            
            
            
            if ($conn->query($insertQuery) === TRUE) {
                echo "$escapedDealTitle ajoutés à la BDD<br>";
            } else {
                echo "Erreur: " . $insertQuery . "<br>" . $conn->error . "<br>";
            }
        }
    }
}