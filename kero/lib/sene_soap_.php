<?php
class SENE_Soap {
	var $__sess;
	var $__api;
	var $__key;
	var $__url;
	var $__ins;
	public function __construct($url, $api, $key){
		$this->__url = $url;
		$this->__api = $api;
		$this->__key = $key;
	}
	public function productList(){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.list');
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function productDetail($id){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.info', ''.$id);
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function productAdd($attributeSetId="9", $jenis="simple", $categories,  $sku, $websites, $name, $desc, $sdesc, $w, $stat, $visibility="4", $price, $tc="0", $mtitle, $mkey, $mdesc, $storev, $qty){
		if(empty($qty))$qty =0;
		if(empty($storev)) $storev = 'default';
		if(empty($attributeSetId)) $attributeSetId = '9';
		if(empty($jenis)) $jenis = 'simple';
		if(empty($visibility)) $visibility = '4';
		if(empty($tc)) $tc = '0';
		
		if(!is_array($categories)){
			$categories = array(2);
		}
		if(!is_array($websites)){
			$websites = array(1);
		}
		
		
		$ent = array(
			$jenis,
			$attributeSetId,
			$sku,
				array(
				'categories' => $categories,
				'websites' => $websites,
				'name' => $name,
				'description' => $desc,
				'short_description' => $sdesc,
				'weight' => $w,
				'status' => $stat,
				'url_key' => '',
				'url_path' => '',
				'visibility' => $visibility,
				'price' => $price,
				'tax_class_id' => $tc,
				'meta_title' => $mtitle,
				'meta_keyword' => $mkey,
				'meta_description' => $mdesc,
				'stock_data' => array(
					'qty' => $qty." ",
					'is_in_stock' => 1,
					'manage_stock' => 1,
					'use_config_manage_stock' => 0
				)
			),
			'default'
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.create', $ent);
			return $res;
		}catch (Exception $e){
			$this->__excep($e,"product add");
		}
	}
	
	public function productGetStok($sku){
		$ent = array(
			$sku
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'cataloginventory_stock_item.list', $ent);
			return $res;
		}catch (Exception $e){
			$this->__excep($e,"product add");
		}
	}
	public function productUpdateStok($sku, $qty){
		$res = $this->productGetStok($sku);
		$tqty = $res[0]['qty'];
		if(empty($qty))$qty =0;
		
		$qty = $qty + $tqty;
		$ent = array(
			$sku,
			array(
				'qty' => $qty." ",
				'is_in_stock' => 1,
				'manage_stock' => 1,
				'use_config_manage_stock' => 0
			)
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'cataloginventory_stock_item.update', $ent);
			return $res;
		}catch (Exception $e){
			$this->__excep($e,"product add");
		}
	}
	public function storeList(){
		
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
			$res = $this->__ins->call($__sess, 'store.list');
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function storeDetail($id){
		
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
			$res = $this->__ins->call($__sess, 'store.info',$id);
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	
	public function categoryList(){
		
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
			$res = $this->__ins->call($__sess, 'catalog_category.tree');
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function attributeSetList(){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
	
			$res = $this->__ins->call($__sess, 'catalog_product_attribute_set.list');
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function attributeList($id){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
	
			$res = $this->__ins->call($__sess, 'product_attribute.list', array($id));
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	public function attributeDetail($id){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$ent = array();
	
			$res = $this->__ins->call($__sess, 'product_attribute.info', array($id));
			return $res;
		}catch (Exception $e){
			$this->__excep($e);
		}
	}
	private function __excep($e,$loc){
		echo 'Error on '.$loc.' at '.__FILE__;
		echo '<pre>';
		print_r($e);
		echo '</pre>';
		die();
	}
}
?>
