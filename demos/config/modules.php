<?php
return array(
	'skyapp'=>array(
		'modules'=>array(
			'skyapp\base',	
		),		
	),
	'epg'=>array(
		'modules'=>array(
			'epg\base',
		),
	),
	'cyy'=>array(
		'class'=>'Sky\cyy\CyyModule',
		'id'=>'Sky\cyy',
	),
);