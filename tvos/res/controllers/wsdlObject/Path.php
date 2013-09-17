<?php
namespace res\controllers\wsdlObject;

class Path {
	
	/**
	 * @var string id
	 */
	public $id;
	
	/**
	 * @var string Name
	 */
	public $name;
	
	/**
	 * @var string Logo
	 */
	public $logo;
	
	/**
	 * @var string CreateTime
	 */
	public $createtime;
	
	/**
	 * @var string 是否有子节点
	 */
	public $haschilds;
	
	/**
	 * @var string ChildsNum
	 */
	public $childsnum;
	
	/**
	 * @var string 集合类型，0指分类，1指集数资源
	 */
	public $settype;
	
	/**
	 * @var string 是否有相关信息
	 */
	public $hasrelation;
	
	/**
	 * @var string 是否有筛选器
	 */
	public $hasfilter;
	
	/**
	 * @var string 动态LOGO
	 */
	public $activelogo;
	
	/**
	 * @var string 客户端行为
	 */
	public $action;
	
	/**
	 * @var string UpdateNum
	 */
	public $updatenum;
	
	/**
	 * @var string 父级筛选器
	 */
	public $parent_filter;
	
	/**
	 * @var string 详情json
	 */
	public $item_json_str;

}