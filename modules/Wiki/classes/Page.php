<?php

class Page {

    private $id;
    private $title;
    private $nameid;
    private $icon;
    private $button;
    private $views;
    private $likes;
    private $likeable;
    private $enabled;
    private $context;
    private $parent;
    private $subpages = array();
    private $originalLink;
    private $editLink;
    private $deleteLink;

    public function __construct($id, $title, $nameid, $icon, $button, $context, $parent, $subpages) {
        $this->id = $id;
        $this->title = $title;
        $this->nameid = $nameid;
        $this->icon = $icon;
        $this->button = $button;
        $this->context = $context;
        $this->parent = $parent;
        $this->subpages = $subpages;
    }

    function hasParent(){
        try{
            if(!(empty($this->parent))){
                return true;
            }
        } catch(Exception $e){
            return false;
        }
        return false;
    }

    function getID(){
        return $this->id;
    }
    function getTitle(){
        return $this->title;
    }
    function getNameID(){
        return $this->nameid;
    }
    function getIcon(){
        return $this->icon;
    }
    function getButton(){
        return $this->button;
    }
    function getContext(){
        return $this->context;
    }
    function getViews(){
        return $this->views;
    }
    function getLikes(){
        return $this->likes;
    }
    function getParent(){
        return $this->parent;
    }
    function getSubPages(){
        return $this->subpages;
    }
    function getSubPagesEnabled(){
        $enPages = array();
        foreach($this->subpages as $page){
            if($page->enabled == 0){
                array_push($enPages, $page);
            }
        }
        return $enPages;
    }
    function getOriginalLink(){
        return $this->originalLink;
    }
    function getEditLink(){
        return $this->editLink;
    }
    function getDeleteLink(){
        return $this->deleteLink;
    }
    function isLikeable(){
        return $this->likeable;
    }
    function isEnabled(){
        return $this->enabled;
    }
    function setEditLink($link){
        $this->editLink = $link;
    }
    function setDeleteLink($link){
        $this->deleteLink = $link;
    }
    function setOriginalLink($link){
        $this->originalLink = $link;
    }
    function setViews($views){
        $this->views = $views;
    }
    function setLikes($likes){
        $this->likes = $likes;
    }
    function setLikeable($likeable){
        $this->likeable = $likeable;
    }
    function setEnabled($enabled){
        $this->enabled = $enabled;
    }
    
}
