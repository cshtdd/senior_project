<?php

class SPW_Project_Details_View_Model extends SPW_Project_Summary_View_Model
{
	//so far we have the same fields, so we'll keep it like this

	public function __construct()
	{
		parent::__construct();
	}

	public function getCurrentSkillNames()
	{
		$resultArray = array();

		foreach ($this->lSkills as $iSkill)
		{
			$resultArray[] = '"'.$iSkill->name.'"';
		}

		$resultStr = join(', ', $resultArray);
		return $resultStr;
	}
}

?>