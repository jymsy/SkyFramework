<?php
namespace advertise\controllers;
class srtOption{
	private $defaultOptions = array();
	
	protected function getOption($key,&$options){
		try {
			return array_key_exists($key, $options)?$options[$key]:$this->defaultOptions[$key];
		}catch (\Exception $e){
			throw new \Exception("Invalid option!", 90001);
		}
	}
	
	protected function setOption($key,$value){
		$this->defaultOptions[$key] = $value;
		return true;
	}
	
	protected function setOptions($options){
		$this->defaultOptions = array_merge($this->defaultOptions,$options);
		return true;
	}
}