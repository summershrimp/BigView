<?php
require_once 'modules/PHPExcel.php';

class ACEImporter
{
	public function xls($fileloc)
	{
		$reader = new PHPExcel_Reader_Excel5();
	    if(!$reader->canRead($filelocbe))
	        return false;
	    
	    $PHPExcel = $reader->load($fileloc);
	    $currentSheet = $PHPExcel->getSheet(0);  /**取得一共有多少列*/
	    $allColumn = $currentSheet->getHighestColumn();     /**取得一共有多少行*/
	    $allRow = $currentSheet->getHighestRow();
	    $currentRow = 1;
	    $currentCol = 0;
	    $fp = fopen($fileloc.".csv","w");
	    for($currentRow=1; $currentRow <= $allRow ; $currentRow++)
	    {
	    	$row = Array();
	    	for($currentColumn='A',$currentCol=0; $currentColumn <= $allColumn ; $currentCol++,$currentColumn++)
	    	{
		        $address = $currentColumn.$currentRow;
		        $row[$currentCol] = $currentSheet->getCell($address)->getValue();
	    	}
	    	if(fputcsv($fp, $row)===false)
	    	{
	    		fclose($fp);
	    		unlink($fileloc.".csv");
	    		return false;
	    	}
	    }
	    return true;
	}
	public function xlsx($fileloc)
	{
		$reader = new PHPExcel_Reader_Excel2007();
	    if(!$reader->canRead($fileloc))
	        return false;
	    
	    $PHPExcel = $reader->load($fileloc);
	    $currentSheet = $PHPExcel->getSheet(0);  /**取得一共有多少列*/
	    $allColumn = $currentSheet->getHighestColumn();     /**取得一共有多少行*/
	    $allRow = $currentSheet->getHighestRow();
	    $currentRow = 1;
	    $currentCol = 0;
	    $fp = fopen($fileloc.".csv","w");
	    for($currentRow=1; $currentRow <= $allRow ; $currentRow++)
	    {
	    	$row = Array();
	    	for($currentColumn='A',$currentCol=0; $currentColumn <= $allColumn ; $currentCol++,$currentColumn++)
	    	{
		        $address = $currentColumn.$currentRow;
		        $row[$currentCol] = $currentSheet->getCell($address)->getValue();
	    	}
	    	if(fputcsv($fp, $row)===false)
	    	{
	    		fclose($fp);
	    		unlink($fileloc.".csv");
	    		return false;
	    	}
	    }
	    return true;
	} 
}

?>