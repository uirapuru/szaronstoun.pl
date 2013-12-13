<?php

class Model_Row_Article
        extends Core_Db_Table_Row
{
    public function __toString()
    {
        return $this->getContent();
    }
    
    public function isLocked()
    {
        return $this->locked == 0 ? false : true;
    }
    
}
