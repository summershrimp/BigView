<?php
require_once 'ACEManager.def.php';
require_once 'ACEUsers.class.php';
class ACEManager extends ACEUsers {
	public function add_user($user_info) {
		if (! $this->privileges [EDIT_USER])
			return false;
		$keys = array_keys ( $user_info );
		$ikeys = "`" . implode ( "`, `", $keys ) . "`";
		$ivals = "'" . implode ( "', '", $user_info ) . "'";
		$sql = "Insert Into `users` ($ikeys)VALUES($ivals)";
		$GLOBALS ['db']->query ( $sql );
		$this->add_ftp_user ( $user_info ['username'], $user_info ['passwd'] );
		$this->execInBackground ( HADOOP_HOME . "/bin/hadoop fs -mkdir /ace/" . $user_info ['username'] );
		return $GLOBALS ['db']->affected_rows ();
	}
	public function get_user_info($user_id) {
		if (! $this->privileges [EDIT_USER])
			return false;
		$sql = "Select * From `users` Where `user_id` = '$user_id'";
		$arr = $GLOBALS ['db']->getRow ( $sql );
		unset ( $arr ['passwd'] );
		return $arr;
	}
	public function list_users()
	{
		if (! $this->privileges [EDIT_USER])
			return false;
		$sql = "Select * From `users`";
		$arr = $GLOBALS['db']->getAll($sql);
		foreach ($arr as $value) {
			unset($value['passwd']);
		}
		return $arr;
	}
	public function change_user($user_id,$user_array)
	{
		if (! $this->privileges [EDIT_USER])
			return false;
		$set_content = "";
		foreach($user_array as $key => $value)
		{
			$set_content .= "`$key` = '$value',";
		}
		$set_content = rtrim($set_content,',');
		$sql = 
				"Update `users` ".
				"Set ".$set_content." ".
				"Where `user_id` = '$user_id' ";
		$GLOBALS['db']->query($sql);

		if($GLOBALS['db']->affected_rows()==1)
			return $user_id;
		return false;
	}
	public function del_user($user_id) {
		if (! $this->privileges [EDIT_USER])
			return false;
		$arr = $this->get_user_info ( $user_id );
		$sql = "DELETE From `users` Where `user_id` = '$user_id'";
		$GLOBALS ['db']->query ( $sql );
		$this->execInBackground ( HADOOP_HOME . "/bin/hadoop fs -rm -r /ace/" . $arr ['username'] );
		$this->del_ftp_user ( $arr ['username'] );
		return $GLOBALS ['db']->affected_rows ();
	}
	private function add_ftp_user($username, $password) {
		if (! $this->privileges [EDIT_USER])
			return false;
		$sql = "Insert Into `ftpusers` (`userid`, `passwd`, `homedir`)VALUES('$username', '$password', '" . FTPHOMEDIR . "/" . $username . "/')";
		$GLOBALS ['db']->query ( $sql );
		mkdir ( FTPHOMEDIR . "/" . $username, 0777, true );
		$handle = fopen ( FTPHOMEDIR . "/" . $username . "/hello.txt", "w+" );
		fwrite ( $handle, "Welcome to ACEHadoop Platform" );
		fclose($handle);
		exec("chmod -R 777 ".FTPHOMEDIR . "/" . $username);
		return $GLOBALS ['db']->affected_rows ();
	}
	private function del_ftp_user($username) {
		if (! $this->privileges [EDIT_USER])
			return false;
		$sql = "Delete From `ftpusers` Where `userid` = '$username'";
		$GLOBALS ['db']->query ( $sql );
		$this->execInBackground ( $this->del_dir ( FTPHOMEDIR . "/" . $username ) );
		return $GLOBALS ['db']->affected_rows ();
	}
	public function upload_chart($chartname, $description, $tmp_file, $file_size) {
		if (! $this->privileges [ADD_CHART])
			return false;
		$fp = fopen ( $tmp_file, "rb" );
		if (! $fp)
			return false;
		$file_data = addslashes ( fread ( $fp, $file_size ) );
		$sql = "Insert Into `chart_storage` (`chartname`,`description`,`uploader_id`,`data`)VALUES('$chartname', '$description', '" . $this->user_info ['user_id'] . "', '$file_data')";
		$GLOBALS ['db']->query ( $sql );
		$ins_id = $GLOBALS ['db']->insert_id ();
		$this->add_chart ( $ins_id );
	}
	public function add_chart($chart_id, $user_id = NULL) {
		if ($user_id != NULL && ! $$this->privileges [EDIT_USER])
			return false;
		if (! $this->privileges [ADD_CHART])
			return false;
		if ($user_id == NULL)
			$user_id = $this->user_info ['user_id'];
		$sql = "Insert Into `user_charts` (`chart_id`, `user_id`)VALUES('$chart_id', '$user_id')";
		$GLOBALS ['db']->query ( $sql );
		return $GLOBALS ['db']->insert_id ();
	}
	public function list_chart(){
		if (! $this->privileges [ADD_CHART])
			return false;
		$sql = "Select * From `chart_storage` Right Join `user_charts` On `user_charts`.`chart_id` = `chart_storage`.`chart_id` Where `user_charts`.`user_id` = '". $this->user_info ['user_id'] . "'";
		$arr = $GLOBALS['db']->getAll($sql);
		return $arr;
	}
	public function output_chart() {
		if (! $this->privileges [ADD_CHART])
			return false;
		$return = "";
		$sql = "Select `chart_storage`.`data` From `chart_storage` " . "Right Join `user_charts` " . "On `user_charts`.`chart_id` = `chart_storage`.`chart_id`" . "Where `user_charts`.`user_id` = '" . $this->user_info ['user_id'] . "'";
		$result = $GLOBALS ['db']->query ( $sql );
		while ( $arr = $GLOBALS ['db']->fetchRow ( $result ) )
			$return .= $arr ['data'] . "\n";
		return $return;
	}
	public function remove_chart($chart_id, $user_id = NULL) {
		if ($user_id != NULL && ! $$this->privileges [EDIT_USER])
			return false;
		if (! $this->privileges [ADD_CHART])
			return false;
		if ($user_id == NULL)
			$user_id = $this->user_info ['user_id'];
		$sql = "Delete From `user_charts` Where `user_id` = '$user_id' and `chart_id` = '$chart_id'";
		$GLOBALS ['db']->query ( $sql );
		return $GLOBALS ['db']->affected_rows ();
	}
	public function upload($ftmp)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		if(file_exists(FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]))
			return false;
		if(!move_uploaded_file($ftmp["tmp_name"], FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]))
			return false;
		if(end(explode('.', $ftmp["name"])) == 'xls' )
		{
			require_once("ACEImporter.class.php");
			//exec("Rscript ".PROGHOMEDIR."/XlsxTrans.R ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]." ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"].".csv");
			$importer = new ACEImporter();
			$importer->xls(FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]);
		}
		elseif(end(explode('.', $ftmp["name"])) == "xlsx")
		{
			require_once("ACEImporter.class.php");
			//exec("Rscript ".PROGHOMEDIR."/XlsxTrans.R ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]." ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"].".csv");
			$importer = new ACEImporter();
			$importer->xlsx(FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]);
		}
		elseif(end(explode('.', $ftmp["name"])) == "arff")
			$this->execInBackground("Rscript ".PROGHOMEDIR."/ArffTrans.R ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"]." ".FTPHOMEDIR."/".$this->user_info['username']."/".$ftmp["name"].".csv");
	}
	public function del_file($fileloc)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		if(is_dir(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc))
			$this->execInBackground($this->del_dir(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc));
		else
			unlink(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc);
		return true;
	}
	public function exec_jar($fileloc, $args = NULL)
	{
		if (! $this->privileges [EXEC_JAR])
			return false;
		$cmd = HADOOP_HOME."/bin/hadoop jar ".FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc." ";
		$cmd .= $args;
		$this->execInBackground($cmd);
		return true;
	}
	public function exec_rscript($fileloc, $args = NULL)
	{
		if (! $this->privileges [EXEC_JAR])
			return false;
		$cmd = "Rscript ".FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc." ";
		$cmd .=  $args;
		$this->execInBackground($cmd);
		return true;
	}
	public function add_group($group_info) {
		if (! $this->privileges [EDIT_GROUP])
			return false;
		$keys = array_keys ( $group_info );
		$ikeys = "`" . implode ( "`, `", $keys ) . "`";
		$ivals = "'" . implode ( "', '", $group_info ) . "'";
		$sql = "Insert Into `user_groups` ($ikeys)VALUES($ivals)";
		$GLOBALS ['db']->query ( $sql );
		return $GLOBALS ['db']->affected_rows ();
	}
	public function list_group()
	{
		if (! $this->privileges [EDIT_GROUP])
			return false;
		$sql = "Select * From `user_groups`";
		$arr = $GLOBALS['db']->getAll($sql);
		return $arr;
	}
	public function get_group_info($group_id)
	{
		if (! $this->privileges [EDIT_GROUP])
			return false;
		$sql = "Select * From `user_groups` Where `group_id` = '$group_id'";
		$arr = $GLOBALS['db']->getRow($sql);
		return $arr;
	}
	public function change_group($group_id,$group_array)
	{
		if (! $this->privileges [EDIT_GROUP])
			return false;
		$set_content = "";
		foreach($group_array as $key => $value)
		{
			$set_content .= "`$key` = '$value',";
		}
		$set_content = rtrim($set_content,',');
		$sql = 
				"Update `user_groups` ".
				"Set ".$set_content." ".
				"Where `group_id` = '$group_id' ";
		$GLOBALS['db']->query($sql);

		if($GLOBALS['db']->affected_rows()==1)
			return $group_id;
		return false;
	}
	public function del_group($group_id)
	{
		if (! $this->privileges [EDIT_GROUP])
			return false;
		$sql = "Delete From `user_groups` Where `group_id` = '$group_id'";
		$GLOBALS['db']->query($sql);
		return $GLOBALS['db']->insert_id();
	}
	public function list_ftp_dir($directory) {
		if (! $this->privileges [ADD_FILE])
			return false;
		$directory = FTPHOMEDIR."/".$this->user_info['username']."/".$directory;
	    $files = array();
	    if(is_dir($directory)) {
	        if($files = scandir($directory)) {
	            $files = array_slice($files,2);
	        }
	    }
	    $ret = Array();
	    foreach ($files as $value) {
	    	if(is_dir($directory.'/'.$value))
	    		$ret[$value] = 'd';
	    	else
	    		$ret[$value] = '-';
	    }
	    return $ret;
	}
	public function list_hdfs_dir($directory)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		$arr = Array();
		exec(HADOOP_HOME."/bin/hadoop fs -ls /ace/".$this->user_info['username']."/".$directory, $arr);
		$arr = array_slice($arr,1);
		$ret = Array();
		foreach ($arr as $value) {
			$tmp = explode(" ", $value);
			if(substr($tmp[0],0,1)=='d')
				$ret[$tmp[count($tmp)-1]]='d';
			else
				$ret[$tmp[count($tmp)-1]]='-';
		}
		return $ret;
	}
	public function hdfs_put($fileloc)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		exec(HADOOP_HOME."/bin/hadoop fs -put ".FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc ." /ace/".$this->user_info['username']."/");
		if(is_dir(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc))
			exec($this->del_dir(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc));
		else unlink(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc);
		return true;
	}
	public function hdfs_get($fileloc)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		$tmp = explode("/",$fileloc);
		exec(HADOOP_HOME."/bin/hadoop fs -get ".$fileloc." ".FTPHOMEDIR."/".$this->user_info['username']."/");
		exec(HADOOP_HOME."/bin/hadoop fs -rm -r ".$fileloc);
	}
	public function hdfs_del($fileloc)
	{
		if (! $this->privileges [ADD_FILE])
			return false;
		var_dump(HADOOP_HOME."/bin/hadoop fs -rm -r ".$fileloc);
		$arr = Array();
		$arr = shell_exec(HADOOP_HOME."/bin/hadoop fs -rm -r ".$fileloc);
		var_dump($arr);
	}
	public function get_file($fileloc)
	{
		$fp = fopen(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc, 'r');
		$ret = fread($fp,filesize(FTPHOMEDIR."/".$this->user_info['username']."/".$fileloc));
		fclose ($fp);
		return $ret;
	}
	private function execInBackground($cmd) {
		if (substr ( php_uname (), 0, 7 ) == "Windows") {
			pclose ( popen ( "start /B " . $cmd, "r" ) );
		} else {
			exec ( $cmd . " > /dev/null &" );
		}
	}
	private function del_dir($dir) {
		if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) == 'WIN') {
			$str = "rmdir /s/q " . $dir;
		} else {
			$str = "rm -Rf " . $dir;
		}
		return $str;
	}
}
?>
