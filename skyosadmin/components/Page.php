<?php
namespace skyosadmin\components;
/*
 *  分页算法类只支持jqgrid控件显示
 */
class Page{
	/*
	 * 当前页
	 */
	public $page;
	/*
	 * 每页多少条
	 */
	public $limit; 
	/*
	 * 总记录数
	*/
	public $count;
	/*
	 * 总页数
	*/
	public $total_pages;
	/*
	 * 从哪一条记录开始显示
	*/
	public $start;
 
	/*
	 * 初始化类配置
	 */
	public function __construct( $count = 0 ){
		$this->count = $count;
		// get the requested page
		$this->page = $_REQUEST['page'];
		// get how many rows we want to have into the grid
		// rowNum parameter in the grid
		$this->limit = $_REQUEST['rows'];
		
	}
	
	//处理分页参数预先存入对像属性字段中
	public function prePage(){
			
		if( $this->count >0 ) {
			$this->total_pages = ceil($this->count/$this->limit);
		} else {
			$this->total_pages = 0;
		}
	
		if ($this->page > $this->total_pages)
			$this->page = $this->total_pages;
	
		// do not put $limit*($page - 1)
		$this->start = $this->limit*$this->page - $this->limit;
	
		if($this->start <0) $this->start = 0;
	}
	
}