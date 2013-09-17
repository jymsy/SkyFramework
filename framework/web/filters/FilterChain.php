<?php
namespace Sky\web\filters;

use Sky\help\SList;
use Sky\Sky;
/**
 * 
 * @author Jiangyumeng
 *
 */
class FilterChain extends SList{
	/**
	 * @var \Sky\base\Controller 执行action的controller
	 */
	public $controller;
	/**
	 * @var \Sky\base\Action 要被过滤的action。
	 */
	public $action;
	/**
	 * @var integer 要执行的过滤器的索引。
	 */
	public $filterIndex=0;
	
	/**
	 * 构造函数。
	 * @param \Sky\base\Controller $controller 执行action的controller
	 * @param \Sky\base\Action $action 要被过滤的action。
	 */
	public function __construct($controller,$action)
	{
		$this->controller=$controller;
		$this->action=$action;
	}
	
	/**
	 * @param \Sky\base\Controller $controller
	 * @param \Sky\base\Action $action
	 * @param array $filters
	 */
	public static function create($controller,$action,$filters)
	{
		$chain=new self($controller,$action);
		
		$actionID=$action->getId();
		foreach($filters as $filter)
		{
			if (is_string($filter))
			{
				if(($pos=strpos($filter,'+'))!==false || ($pos=strpos($filter,'-'))!==false)
				{
					$matched=preg_match("/\b{$actionID}\b/i",substr($filter,$pos+1))>0;
					if(($filter[$pos]==='+')===$matched)
						$filter=InlineFilter::create($controller,trim(substr($filter,0,$pos)));
				}
				else
					$filter=InlineFilter::create($controller,$filter);
			}
			elseif (is_array($filter))
			{
				;
			}
			
			if($filter instanceof Filter)
			{
// 				$filter->init();
				$chain->add($filter);
			}else 
				throw new \Exception('FilterChain can only take instance of Filter.');
		}
		
		return $chain;
	}
	
	/**
	 * 执行在{@link filterIndex}处的过滤器。
	 * 该方法执行之后, {@link filterIndex} 将会自增一。
	 */
	public function run()
	{
		if($this->offsetExists($this->filterIndex))
		{
			$filter=$this->itemAt($this->filterIndex++);
// 			Sky::trace('Running filter '.($filter instanceof InlineFilter ? get_class($this->controller).'.filter'.$filter->name.'()':get_class($filter).'.filter()'),'system.web.filters.FilterChain');
			$filter->filter($this);
		}
		else
			$this->controller->runAction($this->action);
	}
}