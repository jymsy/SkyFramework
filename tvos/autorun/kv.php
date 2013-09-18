<?php
namespace tvos\autorun;

class kv {

	private $soku = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_video',
			'id'=>'v_id',
			'resmark'=>'resmark',
			'expired'=>'expired',
			'title'=>'title',
			'actor'=>'actor',
			'director'=>'director',
			'area'=>'area',
			'publish_company'=>'publish_company',
			'product_company'=>'product_company',
			'img'=>'thumb',
			'tv'=>'tv',
			'bianju'=>'scriptwriter',
			'jianzhi'=>'producer',
			'second_title'=>'alias',
			'year'=>'year',
			'release_date'=>'release_date',
			'type'=>'classfication',
			'score'=>'score',
			'ding'=>'praise',
			'cai'=>'step',
			'comment_count'=>'comment_count',
			'view_count'=>'browse_count',
			'desc'=>'description',
			'category_name'=>'category_name',
			'category'=>'category',
			'total_segment'=>'total_segment',
	);

	private $sokuSite = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_video_site',
			'id'=>'vs_id',
			'resmark'=>'resmark',
			'soku_id'=>'v_id',
			'site'=>'source',
			'timelong'=>'time',
			'edition'=>'definition',
			'resolution'=>'resolution',
			'current_segment'=>'current_segment',
			'expired'=>'expired',
			'action'=>'play_action',
			'price'=>'price',
			'width'=>'width',
			'height'=>'height',
			'run_time'=>'run_time'
	);

	private $sokuUrl = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_video_url',
			'id'=>'vu_id',
			'resmark'=>'resmark',
			'site_id'=>'vs_id',
			'title'=>'title',
			'url'=>'url',
			'index'=>'collection',
			'list_title'=>'list_title',
	);

	private $sokuComment = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_video_comment',
			'id'=>'vc_id',
			'resmark'=>'resmark',
			'soku_id'=>'v_id',
			'title'=>'title',
			'content'=>'content',
			'date'=>'date',
	);

	private $kowoTop = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_music_top',
			'resmark'=>'resmark',
			'title'=>'title',
			'singer'=>'singer',
			'url'=>'url',
			'resource'=>'resource',
			'pageindex'=>'page_index',
			'lrc'=>'lrc',
	);

	private $poster = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_playbill',
			'resmark'=>'resmark',
			'type'=>'type',
			'picture_url'=>'picture_url',
			'small_picture_url'=>'small_picture_url',
			'picture_size'=>'picture_size',
			'picture_name'=>'picture_name',
			'resmark_relation_id'=>'resmark_relation_id',

	);

	private $channel = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_channel',
			'ch_id'=>'channel_id',
			'ch_name'=>'channel_name',
			'ch_img'=>'channel_img',
			'ch_url'=>'channel_url',
			'category_id'=>'category_id',
			'from' => 'source'
	);

	private $program = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_program',
			'ch_id'=>'channel_id',
			'pg_id'=>'program_id',
			'subtype'=>'res_classfication',
			'pg_name'=>'program_name',
			'begintime'=>'begintime',
			'endtime'=>'endtime',
			'type'=>'epg_type',
			'timelong'=>'time',
			'year'=>'year',
			'director'=>'director',
			'actor'=>'actor',
			'area'=>'area',
			'img'=>'image',
			'description'=>'description',
			'lang'=>'language'
	);
	
	private $videoTop = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_top',
			'path'=>'recommend_type',
			'index'=>'sequence',
			'source_id'=>'source_id',
			'source_type'=>'source_type',
			'path_name'=>'recommend_name'
	);
	
	private $videoTop_allInPython = array(
			'areabiz_id'=>'',
			'areabiz_type'=>'',
			'areabiz_schema'=>'skyg_res',
			'areabiz_table'=>'res_extra_weight',
			'soku_id'=>'source_id',
			'score'=>'sequence',
			'type' =>'source_type'
	);

	public function GetArray($areabiz_type,$areabiz_table) {
		switch ($areabiz_table) {
			case 'soku':
				$array = $this->soku;
				break;
			case 'sokuSite':
				$array = $this->sokuSite;
				break;
			case 'sokuUrl':
				$array = $this->sokuUrl;
				break;
			case 'sokuComment':
				$array = $this->sokuComment;
				break;
			case 'kuwoTop':
				$array = $this->kowoTop;
				break;
			case 'playbill':
				$array = $this->poster;
				break;
			case 'channel':
				$array = $this->channel;
				break;
			case 'program':
				$array = $this->program;
				break;
			case 'videoTop':
				$array = $this->videoTop;
				break;
			case 'videoTop_allInPython':
				$array = $this->videoTop_allInPython;
				break;
			default:
				$array = array();
				break;
		}
		return $array;
	}

}