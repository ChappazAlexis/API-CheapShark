<?php

class GamesController {
    function __construct()
    {}

    public function route()
    {
        if (isset($_GET['title']) && !empty (trim($_GET['title'])) ){
            return $this->all($_GET['title']);
        }
        else if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
            return $this->findGameByID(trim($_GET['id']));
        } else{
            return ['status' => 404, 'message' => 'Route not found'];
        }
    } 

    public function all($title) {
        $API = new CheapShark();
        $datas = $API->allGames($title);

        return $datas;
    }

    public function findGameByID($id) {
        
    }
}