<?php 
namespace Peterujah\NanoBlock;
/**
 * Created by SHELL on 30/11/2017.
 * Developer name ujah chigozie | email: ujahchigozie@gmail.com
 */

class Country{
  public const BASIC = __DIR__ . "/all.country.php";
  public const CUSTOM = __DIR__ . "/custom.country.php";
	private $countryArray;
	private $selectFromList;
  
	public function __construct($selectFrom = null, $load = self:BASIC){
		 $this->selectFromList = $selectFrom;
		 $this->countryArray  = require_once($load);
	}
   
	public function arrayOf(){
		return $this->countryArray;
	}
   
	public function get($selected = "", $param = array()){
		$selectArrayList = array();
		$output = "<select id='".$param["id"]."' name='".$param["name"]."' class='".$param["class"]."' title='".$param["title"]."' aria-label='".$param["ariaLabel"]."' data-selected='".$selected."'> ";
		$output .= '<option disabled="disabled" readonly="readonly">'.($param["ariaLabel"] ?? 'Select Country').'</option>';
			if(!empty($this->selectFromList)){
				if( !is_array( $this->selectFromList ) && !$this->selectFromList instanceof Traversable ){
					$this->selectFromList = array($this->selectFromList);
				}
				asort($this->selectFromList);
				if($param["template"] == "phoneCode"){
					foreach($this->selectFromList as $key){
						if(isset($this->countryArray[$key])){
							$output .= "<option value='".$this->countryArray[$key]["code"]."' title='".strtolower($key)."' ".(($this->countryArray[$key]["code"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($this->countryArray[$key]['name']))."</option>";
						}
					}
				}else if($param["template"] == "countryCode"){
					foreach($this->selectFromList as $key){
						if(isset($this->countryArray[$key])){
							$output .= "<option value='".$this->countryArray[$key]["short_name"]."' title='".strtolower($key)."' ".(($this->countryArray[$key]["short_name"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($this->countryArray[$key]['name']))."</option>";
						}
					}
				}else{
					foreach($this->selectFromList as $key){
						if(isset($this->countryArray[$key])){
							$output .= "<option value='".strtolower($this->countryArray[$key]["name"])."' title='".strtolower($key)."' ".(($this->countryArray[$key]["name"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($this->countryArray[$key]['name']))."</option>";
						}
					}
				}
			}else{
				asort($this->countryArray);
				if($param["template"] == "phoneCode"){
					foreach($this->countryArray as $key => $cn){
						$output .= "<option value='".$cn["code"]."' title='".strtolower($key)."' ".(($cn["code"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($cn['name']))."</option>";
					}
				}else if($param["template"] == "phonePrefix"){
					foreach($this->countryArray as $key => $cn){
						$output .= "<option value='".$cn["code"]."' title='".strtolower($key)."' ".(($cn["code"]==strtoupper($selected))?"selected":"").">" . $cn['code'] . "</option>";
					}
				}else if($param["template"] == "countryCode"){
					foreach($this->countryArray as $key => $cn){
						$output .= "<option value='".$cn["short_name"]."' title='".strtolower($key)."' ".(($cn["short_name"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($cn['name']))."</option>";
					}
				}else{
					foreach($this->countryArray as $key => $cn){
						$output .= "<option value='".strtolower($cn["name"])."' title='".strtolower($key)."' ".(($cn["name"]==strtoupper($selected))?"selected":"").">".ucfirst(strtolower($cn['name']))."</option>";
					}
				}
			}
			$output .= "</select>";
			return $output ;
	}


	public function getList($countryCode){
		$setArrayList =  (!empty($this->selectFromList) ? $this->selectFromList : $this->countryArray);
		return $setArrayList[$countryCode];
	}
	
	public function getPath($defaultCountry = "", $get="code"){
		$output = "";
		$setArrayList =  (!empty($this->selectFromList) ? $this->selectFromList : $this->countryArray);
		if(!empty($defaultCountry)){
			foreach($setArrayList as $code => $country){
				if(strtolower($country["name"]) == strtolower($defaultCountry) || strtolower($code) == strtolower($defaultCountry)){
					switch($get){
						case "code": $output .= $code; break;
						case "name": $output .= $country["name"]; break;
						case "short_name": $output .= $country["short_name"]; break;
						case "currency": $output .= $country["currency"]; break;
						case "currency_symbol": $output .= $country["currency_symbol"]; break;
						case "prefix": $output .= $country["code"]; break;
						case "list": return $country; break;
					}
					break;
				}
			}
		}
		return $output;
	}
	
	public function localCurrency($countryCode){
		$currencyType = null;
		$countryCode = (!empty($countryCode) ? $countryCode : "US");
		if(isset($this->countryArray[strtoupper($countryCode)])){
			$getList = $this->countryArray[strtoupper($countryCode)];
			$currencyType = (!empty($getList['currency_symbol']) ? $getList['currency_symbol'] :  (!empty($getList['currency']) ? $getList['currency'] : null));
		}
		
		return $currencyType;
	}
}
