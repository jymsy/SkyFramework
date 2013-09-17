<?php
namespace res\controllers\wsdlObject;

class Category {

	/**
	 * @var string id
	 */
	public $id;
	
	/**
	 * @var string name
	 */
	public $name;
	
	/**
	 * @var string 父id
	 */
	public $parent;
	
	/**
	 * @var string Logo
	 */
	public $logo;
	
	/**
	 * @var string Logo_s
	 */
	public $logo_s;
	
	/**
	 * @var string Child
	 */
	public $child;
	
	/**
	 * @var string action
	 */
	public $action;
	
// 	/**
// 	 * @var string Path
// 	 */
// 	public $path;
	
	/**
	 * @var string 子资源总数
	 */
	public $childsnum;
	
	/**
	 * @var string 子资源每日更新数
	 */
	public $updatenum;
		
}