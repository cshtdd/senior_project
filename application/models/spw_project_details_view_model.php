<?php

class SPW_Project_Details_View_Model extends SPW_Project_Summary_View_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCurrentSkillNames()
	{
		if (isset($this->lSkills) && count($this->lSkills) > 0)
		{
			$resultArray = array();

			foreach ($this->lSkills as $iSkill)
			{
				$resultArray[] = '"'.$iSkill->name.'"';
			}

			$resultStr = join(', ', $resultArray);

			return $resultStr;
		}
		else
		{
			return '';
		}
	}
}

?>