<?php
namespace Sky\help;

/**
 * Html是一个提供了一系列创建HTML视图帮助方法的静态类。
 * @author Jiangyumeng
 *
 */
class Html{
	/**
	 * @var boolean 是否生成特殊属性值。默认为true。HTML5的话可设置为false.
	 */
	public static $renderSpecialAttributesValue=true;
	/**
	 * @var boolean 是否关闭单一标签。默认为true。HTML5的话可设置为false.
	 */
	public static $closeSingleTags=true;
	/**
	 * 将指定字符串编码为HTML实体。
	 * @param string $text 要编码的数据
	 * @return string 编码后的数据
	 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
	 */
	public static function encode($text){
		return htmlspecialchars($text,ENT_QUOTES,\Sky\Sky::$app->charset);
	}
	
	/**
	 * 创建image标签。
	 * @param string $src 图片URL
	 * @param string $alt 要显示的替代文字
	 * @param array $htmlOptions additional HTML attributes (see {@link tag}).
	 * @return string 生成的image标签
	 */
	public static function image($src,$alt='',$htmlOptions=array()){
		$htmlOptions['src']=$src;
		$htmlOptions['alt']=$alt;
		return self::tag('img',$htmlOptions);
	}
	
	/**
	 * Generates an HTML element.创建一个HTML标签。
	 * @param string $tag 标签名
	 * @param array $htmlOptions 元素属性。值将会通过{@link encode()}被HTML-encoded.
	 * 如果'encode'属性被设置，而且值为false，其余的属性将不会被HTML-encoded.
	 * @param mixed $content 标签之间要填充的内容。它将不会 HTML-encoded.
	 * 如果为false，意味着没有内容。
	 * @param boolean $closeTag 是否生成闭合标签。
	 * @return string 生成的HTML标签。
	 */
	public static function tag($tag,$htmlOptions=array(),$content=false,$closeTag=true){
		$html='<' . $tag . self::renderAttributes($htmlOptions);
		if($content===false)
			return $closeTag && self::$closeSingleTags ? $html.' />' : $html.'>';
		else
			return $closeTag ? $html.'>'.$content.'</'.$tag.'>' : $html.'>'.$content;
	}
	
	/**
	 * 生成HTML标签属性。
	 * 如果属性值为null将不会生成
	 * 特殊属性，例如 'checked', 'disabled', 'readonly', 会根据它们的boolean值生成。
	 * @param array $htmlOptions 要生成的属性
	 * @return string 生成结果
	 */
	public static function renderAttributes($htmlOptions){
		$specialAttributes=array(
				'async'=>1,
				'autofocus'=>1,
				'autoplay'=>1,
				'checked'=>1,
				'controls'=>1,
				'declare'=>1,
				'default'=>1,
				'defer'=>1,
				'disabled'=>1,
				'formnovalidate'=>1,
				'hidden'=>1,
				'ismap'=>1,
				'loop'=>1,
				'multiple'=>1,
				'muted'=>1,
				'nohref'=>1,
				'noresize'=>1,
				'novalidate'=>1,
				'open'=>1,
				'readonly'=>1,
				'required'=>1,
				'reversed'=>1,
				'scoped'=>1,
				'seamless'=>1,
				'selected'=>1,
				'typemustmatch'=>1,
		);
	
		if($htmlOptions===array())
			return '';
	
		$html='';
		if(isset($htmlOptions['encode'])){
			$raw=!$htmlOptions['encode'];
			unset($htmlOptions['encode']);
		}else
			$raw=false;
	
		foreach($htmlOptions as $name=>$value){
			if(isset($specialAttributes[$name])){
				if($value){
					$html .= ' ' . $name;
					if(self::$renderSpecialAttributesValue)
						$html .= '="' . $name . '"';
				}
			}elseif($value!==null)
				$html .= ' ' . $name . '="' . ($raw ? $value : self::encode($value)) . '"';
		}
	
		return $html;
	}
}