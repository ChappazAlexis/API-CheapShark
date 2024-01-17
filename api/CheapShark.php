<?php

class CheapShark {   
    public function allGames($title) {
        $endpoint = 'games';
        $params = '?title='.$title;
    }

    public function findGameByID($id) {
        $endpoint = 'games';
        $params = '';

    }
}