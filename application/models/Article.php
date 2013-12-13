<?php

class Model_Article
        extends Core_Db_Model
{

    protected $_tableClass = "Model_DbTable_Article";
    
    protected static $_instance;

    /**
     * Singleton
     * @return Model_Articles
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}
