<?php
class ACEGroups
{
	public function group_privileges($gid)
	{
		$priv = Array();
		foreach (range('a', 'z') as $value)
			$priv[$value]=false;
		$sql = "Select `privileges` From `user_groups` Where `group_id` = '$gid'";
		$gpr = $GLOBALS['db']->getOne($sql);
		$gpl = strlen($gpr);
		for($i = 0; $i < $gpl; $i++)
			$priv[substr($gpr, $i, 1)] = true;
		return $priv;
		}
}
?>