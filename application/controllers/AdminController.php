<?php

class AdminController
        extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('admin');

        $this->_auth = Zend_Auth::getInstance();

        if (!$this->_auth->hasIdentity()) {
            if (!in_array($this->_request->getActionName(), array(
                        'login',
                        'add-article-image'
                    ))) {
                $this->_redirect('/login');
                exit;
            }
        }
        else {
            $this->view->layout()->username = $this->_auth->getIdentity()->identity;
        }
    }

    public function loginAction()
    {

        $oForm = new Form_Login();

        if ($this->_request->isPost()) {
            if ($oForm->isValid($this->_request->getPost())) {
                $oUser = new Core_Auth_Adapter_Config(Zend_Registry::get('config')->credentials);
                $oUser->setIdentity($oForm->getValue('email'));
                $oUser->setCredential($oForm->getValue('password'));

                $this->_auth->authenticate($oUser);

                if ($this->_auth->hasIdentity()) {
                    $this->_redirect('/admin');
                    exit;
                }
                else {
                    $iErrorCode = $oUser->authenticate()->getCode();
                    if ($iErrorCode == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND) {
                        $oForm->getElement("email")->addError(
                                $this->view->translate("Użytkownik nie istnieje")
                        );
                    }
                    else if ($iErrorCode == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
                        $oForm->getElement("password")->addError(
                                $this->view->translate("Złe hasło")
                        );
                    }
                    else {
                        $oForm->getElement("email")->addError(
                                $this->view->translate("Użytkownik nie istnieje")
                        );
                    }
                }
            }
        }

        $this->view->form = $oForm;
    }

    public function logoutAction()
    {
        $this->_auth->clearIdentity();
        $this->_redirect('/admin');
    }

       public function addArticleImageAction()
    {

        set_time_limit(120);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $sFilename = time() . "_" . $_FILES["upload"]["name"];
        $sFiletype = $_FILES["upload"]["type"];
        $sFiletmp = $_FILES["upload"]["tmp_name"];
        $iError = $_FILES["upload"]["error"];

        $aAllowedTypes = array(
            "image/jpeg",
            "image/png",
            "image/gif",
        );


        $sUploadUrl = "/img/_upload/";
        $sUploadDir = realpath(rtrim(APPLICATION_PATH . "/../public_html" . $sUploadUrl, "/"));

        $message = '';

        if (!$sUploadDir) {
            $message = "Upload dir not found";
        }
        else {

            $sUploadDir.= "/";

            if ($iError == 0) {
                if (in_array($sFiletype, $aAllowedTypes)) {
                    if (move_uploaded_file($sFiletmp, $sUploadDir . $sFilename)) {
                        $sUrl = $sUploadUrl . $sFilename;
                    }
                    else {
                        $message = "Move error!";
                    }
                }
                else {
                    $message = "Incorrect type!";
                }
            }
            else {
                $message = "Upload error!";
            }
        }

        $funcNum = $this->_request->getParam('CKEditorFuncNum');
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$sUrl', '$message');</script>";
    }

    public function saveArticleAction()
    {
        set_time_limit(120);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $aParams = $this->_request->getPost();
        
        $oArticle = Model_Article::getInstance()->getTable()->getById($aParams["id"]);
        $oArticle->content = stripslashes($aParams["content"]);
        $oArticle->save();
        
        Model_Article::getInstance()->cleanCache("getById");
        
        $this->_redirect("/admin");
    }
    public function indexAction()
    {
        $oArticles = Model_Article::getInstance()->fetchAll();

        if ($this->_request->isPost()) {
            $iId = $this->_request->getParam('id', null);

            if ($iId !== null) {
                $this->view->id = $iId;
            }
        }

        $this->view->articles = $oArticles;
    }

}

