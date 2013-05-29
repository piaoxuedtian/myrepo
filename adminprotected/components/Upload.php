<?php
/**
 * $upload: CUploadedFile::getInstance;
 * $type:  artilce product
 * $act:  create update
 * $fileurl:  delete old fileurl when update
 */
class Upload
{
	public static function createFile($upload,$type = 'image',$act = 'update',$fileurl=''){
		if(!empty($fileurl)&&$act==='update'){
			$deleteFile=Yii::app()->basePath.'/../data/'.$fileurl;
			if(is_file($deleteFile))
				unlink($deleteFile);
		}
		$uploadDir=Yii::app()->basePath.'/../data/'.$type.'/'.date('Y-m',time());
		self::recursionMkDir($uploadDir);
		$filename=time().'-'.rand().'.'.$upload->extensionName;
		//图片存储路径
		$fileurl=$type.'/'.date('Y-m',time()).'/'.$filename;
		//存储绝对路径
		$uploadPath=$uploadDir.'/'.$filename;
		if($upload->saveAs($uploadPath)){
			return $fileurl;
		}else{
			return null;
		}
	}
	private static function recursionMkDir($dir){
		if(!is_dir($dir)){
			if(!is_dir(dirname($dir))){
				self::recursionMkDir(dirname($dir));
				mkdir($dir,'0777');
			}else{
				mkdir($dir,'0777');
			}
		}
	}
}