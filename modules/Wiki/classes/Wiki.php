<?php

class Wiki {

    private $_db;
    private $queries;
    private $pagesAmount=0;
    private $wikipages;
    private $wikipages_array = array();
    private $settings;

    public function __construct($queries) {
        $this->_db = DB::getInstance();
        $this->queries = $queries;
    }

    public function getSettings(){
        $this->settings = DB::getInstance()->get('wiki_settings', array('name', '=', 'home_page'))->results();
        return $this->settings[0]->value;
    }

    public function getPages() {
        $this->wikipages = DB::getInstance()->get("wiki_pages", array("id", "<>", 0))->results();
        return $this->wikipages;
    }

    public function initPage($page){
        array_push($this->wikipages_array, $page);
    }

    public function getViewLink($link){
        return "/"."wiki"."/".$link;
    }

    /*public function checkForUpdate(){
        //y($sql, $params = array(), $fetch_method = PDO::FETCH_OBJ) {
        $this->_db->query('SHOW COLUMNS FROM '. $this->_db->_prefix .'wiki_pages WHERE Field = ?', array('views'),'');
    }*/

    public function showColumns($col) {
        $col = $this->_db->_prefix . $col;
        $sql = "SHOW COLUMNS FROM".$this->_db->_prefix."wiki_pages WHERE Field = '{$col}'";

        if (!$this->query($sql)->error()) {
            return $this->_query->rowCount();
        }

        return false;
    }

    public function updateViews($nameid){
        foreach($this->wikipages as $page){
            if($page->nameid == $nameid){
                $views = (int) $page->views;
                $this->_db->increment('wiki_pages', $page->id, 'views');
                break;
            }
        }
    }

    public function initPages($staffmode){
        foreach($this->wikipages as $page){
            $_page = new Page(
                Output::getClean($page->id),
                Output::getClean($page->title),
                Output::getClean($page->nameid),
                (($page->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($page->icon)) : htmlspecialchars_decode($page->icon)),
                (($page->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($page->button)) : htmlspecialchars_decode($page->button)),
                (($page->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($page->context)) : htmlspecialchars_decode($page->context)),
                Output::getClean($page->parent),
                $this->getSubPages($page, $staffmode)
            );
            $_page->setViews($page->views);
            $_page->setLikes($page->likes);
            $_page->setLikeable($page->likeable);
            $_page->setEnabled($page->enabled);
            $_page->setOriginalLink($this->getViewLink(Output::getClean($page->nameid)));
            $_page->setEditLink(URL::build('/panel/wiki/', 'action=edit&id=' . Output::getClean($page->id)));
            $_page->setDeleteLink(URL::build('/panel/wiki/', 'action=delete&id=' . Output::getClean($page->id)));
            $this->initPage($_page);
            $this->pagesAmount++;
        }
    }

    // Like System

    public function getLikesByPage($page){
        try{
            $data = DB::getInstance()->get('wiki_likes', array('pageid', '=', $page))->results();
            return $data;
        } catch(Throwable $e){ return null; }
    }

    public function isPageLikedByUser($username, $nameid){
        $status = false;
        try{
            $data = DB::getInstance()->get('wiki_likes', array('username', '=', $username))->results();
            foreach($data as $liked)
            {
                if ($liked->pageid == $nameid)
                {
                    $status = true;
                    break;
                }
            }
            return $status;
        } catch(Throwable $e){ return $status; }
    }

    public function isPageLikedByUserAsString($username, $nameid){
        try{
            $data = DB::getInstance()->get('wiki_likes', array('username', '=', $username))->results();
            foreach($data as $liked)
            {
                if ($liked->pageid == $nameid)
                {
                    return 'true';
                    break;
                }
            }
            return 'false';
        } catch(Throwable $e){ echo($e); return 'false'; }
    }

    public function reaction($page, $username, $action){
        if($action){
            if($this->like($page, $username)){
                return true;
            } else {
                return false;
            }
        } else {
            if($this->unlike($page, $username)){
                return true;
            } else {
                return false;
            }
        }
    }

    public function like($nameid, $username){
        $status = false;
        foreach($this->wikipages as $page){
            if($page->nameid == $nameid){
                if($this->_db->insert('wiki_likes', array('username' => $username,'pageid' => $nameid))){
                    $likes = (int) $page->likes;
                $this->_db->increment('wiki_pages', $page->id, 'likes');
                    $status = true;
                } else {
                    $status = false;
                }
                break;
            }
        }
        return $status;
    }

    public function unlike($nameid, $username){
        $status = false;
        foreach($this->wikipages as $page){
            if($page->nameid == $nameid){
                $like_record = $this->_db->get('wiki_likes', array('username', '=', $username))->results();
                foreach($like_record as $record){
                    if($record->username == $username){
                        if($record->pageid == $page->nameid){
                            if($this->_db->delete('wiki_likes', array('id', '=', $record->id))){
                                $likes = (int) $page->likes;
                                $this->_db->decrement('wiki_pages', $page->id, 'likes');
                                $status = true;
                            } else {
                                $status = false;
                            }
                            break;
                        }
                    }
                }
                break;
            }
        }
        return $status;
    }

    public function isEnabled($page){
        $status = false;
        try{
            if($page->isEnabled() == 1){
                $status = true;
            }
        } catch(Exception $e){ $e->getMessage(); }
        return $status;
    }

    public function isPage($page){
        $status = false;
        try{
            if(!$this->hasParent($page)){
                if(!$this->hasSub($page)){
                    $status = true;
                }
            }
        } catch(Exception $e){ $e->getMessage(); }
        return $status;
    }

    public function isCategory($page){
        $status = false;
        try{
            if(!$this->hasParent($page)){
                if($this->hasSub($page)){
                    $status = true;
                }
            }
        } catch(Exception $e){ $e->getMessage(); }
        return $status;
    }
    
    public function hasSub($page){
        if(isset($page->subpages) || !is_null($page->subpages) && !empty($page->subpages)){
            return true;
        } else {
            return false;
        }
    }

    public function hasParent($page){
        if(!is_null($page->parent) || isset($page->parent) || $page->parent != "null" || !empty($page->parent)) {
            return true;
        } else {
            return false;
        }
    }

    function isPageExists($page){
        foreach($this->wikipages as $spage){
            if($spage->nameid == $page){
                return true;
            }
        }
        return false;
    }

    function isPageLikeable($page){
        foreach($this->wikipages as $spage){
            if($spage->nameid == $page){
                if($spage->likeable) {
                    return true;
                }
            }
        }
        return false;
    }

    function getSubPages($page, $staffmode){
        $output_array = array();
        foreach($this->wikipages as $spage){
            if(isset($spage->parent) || is_null($spage->parent) || $spage->parent != "null" || !empty($page->parent)){
                if($spage->parent == $page->nameid){
                    if($staffmode){
                        try{
                            array_push($output_array, array(
                                'id' => Output::getClean($spage->id),
                                'title' => Output::getClean($spage->title),
                                'nameid' => Output::getClean($spage->nameid),
                                'parent' => Output::getClean($spage->parent),
                                'views' => Output::getClean($spage->views),
                                'likes' => Output::getClean($spage->likes),
                                'likeable' => Output::getClean($spage->likeable),
                                'enabled' => Output::getClean($spage->enabled),
                                'icon' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->icon)) : htmlspecialchars_decode($spage->icon)),
                                'button' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->button)) : htmlspecialchars_decode($spage->button)),
                                'context' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->context)) : htmlspecialchars_decode($spage->context)),
                                'original_link' => $this->getViewLink(Output::getClean($page->nameid)),
                                'edit_link' => (URL::build('/panel/wiki/', 'action=edit&id=' . Output::getClean($spage->id))),
                                'delete_link' => (URL::build('/panel/wiki/', 'action=delete&id=' . Output::getClean($spage->id)))
                            ));
                        } catch(Exception $e){}
                    } else {
                        if($spage->enabled == "1"){
                            try{
                                array_push($output_array, array(
                                    'id' => Output::getClean($spage->id),
                                    'title' => Output::getClean($spage->title),
                                    'nameid' => Output::getClean($spage->nameid),
                                    'parent' => Output::getClean($spage->parent),
                                    'views' => Output::getClean($spage->views),
                                    'likes' => Output::getClean($spage->likes),
                                    'likeable' => Output::getClean($spage->likeable),
                                    'enabled' => Output::getClean($spage->enabled),
                                    'icon' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->icon)) : htmlspecialchars_decode($spage->icon)),
                                    'button' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->button)) : htmlspecialchars_decode($spage->button)),
                                    'context' => (($spage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($spage->context)) : htmlspecialchars_decode($spage->context)),
                                    'original_link' => $this->getViewLink(Output::getClean($page->nameid)),
                                    'edit_link' => (URL::build('/panel/wiki/', 'action=edit&id=' . Output::getClean($spage->id))),
                                    'delete_link' => (URL::build('/panel/wiki/', 'action=delete&id=' . Output::getClean($spage->id)))
                                ));
                            } catch(Exception $e){}
                        }
                    }
                }
            }
        }
        return $output_array;
    }

    public function getPageButton($pageName){
        foreach($this->wikipages as $spage){
            if($spage->nameid == $pageName){
                return $spage->button;
            }
        }
    }

    public function getPageTitle($pageName){
        foreach($this->wikipages as $spage){
            if($spage->nameid == $pageName){
                return $spage->title;
            }
        }
    }
    public function getPageCategory($pageName){
        foreach($this->wikipages as $spage){
            if($spage->nameid == $pageName){
                return $spage->parent;
            }
        }
    }
    
    public function getPagesArray(){
        return $this->wikipages_array;
    }

    public function getPagesAmount(){
        return $this->pagesAmount;
    }

}
