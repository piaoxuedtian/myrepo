<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminIdentity extends CUserIdentity
{
	private $_id;
 
	public function authenticate()
	{
		$adminname=strtolower($this->username);
		$admin=Admin::model()->find('LOWER(adminname)=?',array($adminname));
		if($admin===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$admin->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$admin->id;
			$this->username=$admin->adminname;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}
 
	public function getId()
	{
		return $this->_id;
	}
}
?>
