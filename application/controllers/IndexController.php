<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->AjaxContext()->addActionContexts(array(
            "index" => "json",
            "audio" => "json",
            "gallery" => "json",
            "hardware" => "json",
        ))->initContext();
    }

    public function indexAction() {
        $iId = $this->_request->getParam('id', null);

        if ($iId == null) {
            return;
        }

        $oArticle = Model_Article::getInstance()->getById($iId);

        if ($oArticle) {
            $this->view->headTitle = strip_tags($this->view->headTitle($oArticle->getTitle(), Zend_View_Helper_Placeholder_Container_Abstract::APPEND));

            if ($this->_helper->AjaxContext()->getCurrentContext() == "json") {
                $this->view->id = $iId;
                $this->view->status = 'ok';
                $this->view->content = $oArticle->getContent();
            } else {
                $this->view->article = $oArticle;
            }
        } else {
            if ($this->_helper->AjaxContext()->getCurrentContext() == "json") {
                $this->view->status = 'error';
                $this->view->message = 'Strony nie znaleziono';
            }
        }
    }

    public function audioAction() {
        if ($this->_helper->AjaxContext()->getCurrentContext() == "json") {
            $this->view->status = 'ok';
            $this->view->content = $this->view->render('index/audio.phtml');
        }
    }

    public function galleryAction() {
        if ($this->_helper->AjaxContext()->getCurrentContext() == "json") {
            $this->view->status = 'ok';
            $this->view->content = $this->view->render('index/gallery.phtml');
        }
    }

    public function hardwareAction() {
        if ($this->_helper->AjaxContext()->getCurrentContext() == "json") {
            $this->view->status = 'ok';
            $this->view->content = $this->view->render('index/hardware.phtml');
        }
    }

}

