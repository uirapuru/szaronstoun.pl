<?php

class Model_DbTable_Article
        extends Core_Db_Table
{
    protected $_prefix = "article";
    protected $_name = 'article';
    
    protected $_rowClass = 'Model_Row_Article';
    
}
