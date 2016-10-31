<?php

/**
 * Class get information to service.
 *
 * @author juanfrasr
 */
class kpaxSrv {

    protected $url = "http://localhost:8080/webapps/svrKpax/";
    private $key;
    private $apiKey = "e4afd792d8730dded98e67ac6e9752bd35e764bc"; // Public API key generated by elgg
    private $oauthKpax = null;

    public function __construct($userName = "admin") { //ha de ser kPAXadmin o admin per defecte???
        $this->oauthKpax = new kpaxOauth();
        $userName = str_replace("uoc.edu_", "", $userName); //Case UOC login
        $body = 'username=' . trim($userName . "&apikey=" . $this->apiKey);
        $_SESSION["campusSession"] = $this->service("user/sign/elgg", "POST", $body);
    }

    public function getKey() {
        return $this->key;
    }
    
    public function oauth($key,$secret){
        $this->oauthKpax->setKeySecret($key, $secret);
    }
        
    private function service($action, $type = "GET", $body = "", $header = "application/json") {
        $url = $this->oauthKpax->getSignature($type, $this->url . $action);
        $options = array('method' => $type,
            'header' => 'Content-type: ' . $header,
            'content' => $body);
        $type_post = array('http' => $options);
        $context = stream_context_create($type_post);

        return file_get_contents($url, false, $context);
    }

    public function getGame($gameId, $campusSession) {

        return json_decode($this->service("game/" . $campusSession . "/get/" . $gameId));
    }

//NOU - El·liminat
//    public function getListGames($campusSession) {
//        //var_dump($this->service("game/" . $campusSession . "/list")); //Volem que torni l'objecte
//        json_decode($this->service("game/" . $campusSession . "/list"));
//    }

    public function addLikeGame($campusSession, $containerId, $productId) {
        $body = 'secretSession=' . $campusSession . '&containerId=' . $containerId;
        $this->service("game/like/" . $productId . "/add", "POST", $body);
    }

    public function delLikeGame($campusSession, $containerId, $productId) {
        $body = 'secretSession=' . $campusSession . '&containerId=' . $containerId;
        $this->service("game/like/" . $productId . "/del", "POST", $body);
    }

    public function getLikesGame($campusSession, &$entity) {
        $listLike = json_decode($this->service("game/like/" . $campusSession . "/list/" . $entity->getGuid()));
        return $listLike;
    }

    public function getLikeGame($campusSession, $idEntity) {
        $objLike = json_decode($this->service("game/like/" . $campusSession . "/get/" . $idEntity));
        return $objLike;
    }

//NOU - El·liminat
//    public function addGame($campusSession, $name, $idGame) {
//        $body = 'secretSession=' . $campusSession . '&name=' . $name . '&idGame=' . $idGame;
//        return $this->service("game/add", "POST", $body);
//    }

    public function delGame($campusSession, $idGame) {
        $body = 'secretSession=' . $campusSession;
        return $this->service("game/delete/" . $idGame, "POST", $body);
    }

    public function getScore($gameUid) {
        return json_decode($objScore = $this->service("game/score/" . $gameUid . "/list"));
    }

//NOU
// Begin Author: rviguera
    public function getListGames($campusSession, $idOrderer, $idFilterer, $fields, $values) {
        $body = 'secretSession=' . $campusSession;
    $count = count($fields);
    for ($i = 0; $i < $count; $i++) {
        $body = $body . "&fields=" . $fields[$i] . "&values=" . $values[$i];
    }
        return json_decode($this->service("game/" . $campusSession . "/list/" . $idOrderer . "/" . $idFilterer, "POST", $body));
    }

    public function getUserListGames($username, $campusSession) {
        //$body = 'secretSession=' . $campusSession . "&username=" . $username;
        return json_decode($this->service("game/" . $campusSession . "/listDev/" . $username));
        //var_dump($this->service("game/" . $campusSession . "/list/" . $username));
    }

    public function addGame($campusSession, $name, $idGame, $idCategory, $creationDate) {
        $body = 'secretSession=' . $campusSession . '&name=' . $name . '&idGame=' . $idGame . "&idCategory=" . $idCategory . "&creationDate=" . $creationDate;
        return $this->service("game/add", "POST", $body);
    }

    public function getCategories($campusSession) {
        $listCategories = json_decode($this->service("game/category/" . $campusSession . "/list/"));
        return $listCategories;
    }

    public function getCategory($campusSession, $idCategory) {
        $objCategory = json_decode($this->service("game/category/" . $campusSession . "/get/" . $idCategory));
        return $objCategory;
    }

    public function getCommentsGame($campusSession, $idGame) {
        $listComments = json_decode($this->service("game/comment/" . $campusSession . "/list/" . $idGame));
        return $listComments;
    }

    public function addCommentGame($campusSession, $idGame, $idComment) {
        $body = 'secretSession=' . $campusSession . "&idGame=" . $idGame;
        return $this->service("game/comment/" . $idComment . "/add", "POST", $body);
    }

    public function delCommentGame($campusSession, $idComment) {
        $body = 'secretSession=' . $campusSession . '&containerId=' . $containerId;
        return $this->service("game/comment/" . $idComment . "/del", "POST", $body);
    }

    public function getTagsGame($campusSession, $idGame) {
        $listTags = json_decode($this->service("game/tag/" . $campusSession . "/list/" . $idGame));
        return $listTags;
    }

    public function addDelTagsGame($campusSession, $idGame, $tagsCommaSeparated) {
        $body = 'secretSession=' . $campusSession . '&tags=' . $tagsCommaSeparated;
        return $this->service("game/tag/" . $idGame . "/addDel", "POST", $body);
    }
// Fi NOU
}

?>