<?php
declare(strict_types=1);


namespace Nago\Component\Http;


class Form
{
	/**
	 * selectタグを作成する
	 * @param string $name selectの名前
	 * @param array $hash optionタグに使用する連想配列。keyが値、valueが表示する文字列
	 * @param string $selected selected対象となるキー
	 * @param array $attr selectにidやclassを追加できる
	 *				array('id' => 'foo', 'class' => 'bar')
	 * @return string
	 */
	public function select($name, $hash, $selected='', $attr=null, $default=null){
		$option_str = $this->createAttribute($attr);

		$html = '';
		$html .= "<select name=\"{$name}\"{$option_str}>";
		if($default){
			$label = current($default);
			$key = key($default);
			$html .= "<option value=\"{$key}\">{$label}</option>";
		}
		foreach($hash as $key => $value){
			$selected_str = ($selected == $key)?' selected' : '';
			$html .=  "<option value=\"{$key}\"{$selected_str}>{$value}</option>";
		}
		$html .= '</select>';
		return $html;
	}
	public function selectArray($name, $array, $selected='', $attr=null){
		$option_str = $this->createAttribute($attr);

		$html = '';
		$html .= "<select name=\"{$name}\"{$option_str}>";
		$index = 1;
		foreach($array as $value){
			$selected_str = ($selected == $index)?' selected' : '';
			$html .=  "<option value=\"{$index}\"{$selected_str}>{$value}</option>";
			$index++;
		}
		$html .= '</select>';
		return $html;
	}
	public function selectGroup($name, $hash, $selected='', $attr=null){
		$option_str = $this->createAttribute($attr);

		$html = '';
		$html .= "<select name=\"{$name}\"{$option_str}>";
		foreach($hash as $label => $_array){
			if(is_array($_array)){
				$html .=  "<optgroup label=\"{$label}\">";
				foreach($_array as $key => $value){
					$selected_str = ($selected == $key)?' selected' : '';
					$html .=  "<option value=\"{$key}\"{$selected_str}>{$value}</option>";
				}
				$html .= '</optgroup>';
			}else{
				$key = $label;
				$value = $_array;
				$html .=  "<option value=\"{$key}\">{$value}</option>";
			}
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * タグの属性値を生成する
	 * @param $attr
	 * @param string $parent_key
	 * @return string
	 */
	private function createAttribute($attr, $parent_key='') : string{
		$option_str = '';
		if($attr && $this->isHash($attr)){
			foreach($attr as $key => $value){
				$option_str .= " {$key}=\"{$value}{$parent_key}\"";
			}
		}
		return $option_str;
	}
	public function isHash($var){
		return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
	}
	public static function safeCheckbox($key){
		if(!isset($_POST[$key]) || !is_array($_POST[$key]))return array();
		return $_POST[$key];
	}
	public function h($str){
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
	public function radio($name, $hash, $checked='', $attr=null){
		$html = '';
		foreach($hash as $key => $value){
			$option_str = $this->createAttribute($attr, $key);
			$checkedStr = '';
			if($checked == $key)$checkedStr = 'checked';
			$html .= "<input type=\"radio\" name=\"{$name}\" value=\"{$key}\" {$option_str} {$checkedStr}>{$value}";
		}
		return $html;
	}
	public function safe($key, $value=null){
		if($value)return $this->h($value);
		if(isset($_POST[$key])){
			return isset($_POST[$key]) ? $this->h($_POST[$key]):'';
		}
		if(isset($_GET[$key])){
			return isset($_GET[$key]) ? $this->h($_GET[$key]):'';
		}
		return '';
	}
}
