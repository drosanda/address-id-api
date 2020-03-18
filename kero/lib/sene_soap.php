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
			return 0;
			$this->__excep($e,"product list");
		}
	}
	public function productDetail($id){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.info', ''.$id);
			return $res;
		}catch (Exception $e){
			return 0;
			$this->__excep($e,"product_detail");
		}
	}
	public function productAdd($attributeSetId, $jenis="configurable", $categories,  $sku, $sku_simple, $websites, $name, $desc, $sdesc, $w, $stat, $visibility="4", $price, $tc="0", $mtitle, $mkey, $mdesc, $storev, $qty){
		if(empty($qty))$qty =0;
		if(empty($storev)) $storev = 'default';
		if(empty($attributeSetId)) $attributeSetId = '21';
		if(empty($jenis)) $jenis = 'configurable';
		if(empty($visibility)) $visibility = '4';
		if(empty($tc)) $tc = '0';
		
		if(!is_array($categories)){
			$categories = array(2);
		}
		if(!is_array($websites)){
			$websites = array(1);
		}
		//$sku_simple = 'ZRK3.TI.3023LB';
		/*$sku_simple_length = strlen($sku_simple);
		$last_digit = $sku_simple_length-2;
		$sku_simple_sub = substr($sku_simple, -$sku_simple_length, $last_digit);
		
		if ($sku_simple_sub == $sku_configurable) $sku_simple = $sku_simple_sub;*/
		
		$ent = array(
			$jenis,
			$attributeSetId,
			$sku,
				array(
				'categories' => $categories, 
				'websites' => $websites, //Exist
				'name' => $name, // Exist
				'description' => $desc, // Exist
				'short_description' => $sdesc, //Exist
				'weight' => $w,//Exist
				'status' => $stat, //Exist
				'url_key' => '', 
				'url_path' => '',
				'visibility' => $visibility, //Exist
				'price' => $price, // Exist
				'tax_class_id' => $tc, //Exist
				'meta_title' => $mtitle, 
				'meta_keyword' => $mkey,
				'meta_description' => $mdesc,
				'stock_data' => array(
					'qty' => $qty." ",
					'is_in_stock' => 1,
					'manage_stock' => 1,
					'use_config_manage_stock' => 0
				),
				'associated_skus' => $sku_simple // Tambahan
			),
			'default'
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'product.create', $ent);
			return $res;
		}catch (Exception $e){
			return $e;
			return 0;
			$this->__excep($e,"product add");
		}
		
	}
	
	public function updateConf($attributeSetId, $jenis="configurable", $categories,  $sku, $sku_simple, $websites, $name, $desc, $sdesc, $w, $stat, $visibility="4", $price, $tc="0", $mtitle, $mkey, $mdesc, $storev, $qty){
		if(empty($qty))$qty =0;
		if(empty($storev)) $storev = 'default';
		if(empty($attributeSetId)) $attributeSetId = '21';
		if(empty($jenis)) $jenis = 'configurable';
		if(empty($visibility)) $visibility = '4';
		if(empty($tc)) $tc = '0';
		
		if(!is_array($categories)){
			$categories = array(2);
		}
		if(!is_array($websites)){
			$websites = array(1);
		}
		
		$ent = array(
			$sku,
				array(
				'categories' => $categories, 
				'websites' => $websites, //Exist
				'name' => $name, // Exist
				'description' => $desc, // Exist
				'short_description' => $sdesc, //Exist
				'weight' => $w,//Exist
				'status' => $stat, //Exist
				'url_key' => '', 
				'url_path' => '',
				'visibility' => $visibility, //Exist
				'price' => $price, // Exist
				'tax_class_id' => $tc, //Exist
				'meta_title' => $mtitle, 
				'meta_keyword' => $mkey,
				'meta_description' => $mdesc,
				'stock_data' => array(
					'qty' => $qty." ",
					'is_in_stock' => 1,
					'manage_stock' => 1,
					'use_config_manage_stock' => 0
				),
				'associated_skus' => $sku_simple // Tambahan SKu
			),
			'default'
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.update', $ent);
			return $res;
		}catch (Exception $e){
			return $e;
			return 0;
			$this->__excep($e,"product add");
		}
	}
	
	public function productAttribute($ukuran){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product_attribute.options', array('24'));
			
			foreach ($res as $opt) {
			if ($opt['label'] == 'l') {
			$ukuran = $opt['value'];
				}
			}
			if (!isset($ukuran)) {
			$ukuran = $proxy->call($__sess, 'catalog_product_attribute.addOption', array('24',$ukuran));
			}
			return $res;
		}catch (Exception $e){
			return $e;
			return 0;
			$this->__excep($e,"product attribute");
		}
	}
	public function productSimpleAdd($attributeSetId, $jenis="simple", $categories,  $sku, $ukuran, $color, $websites, $name, $desc, $sdesc, $w, $stat, $visibility="1", $price, $tc="0", $mtitle, $mkey, $mdesc, $storev, $qty){
		if(empty($qty))$qty =0;
		if(empty($storev)) $storev = 'default';
		if(empty($attributeSetId)) $attributeSetId = '21';
		if(empty($jenis)) $jenis = 'simple';
		if(empty($visibility)) $visibility = '1';
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
				'size' => $ukuran,
				'color' => $color,
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
			$res = $this->__ins->call($__sess, 'product.create', $ent);
			return $res;
		}catch (Exception $e){
			return $e;
			return 0;
			$this->__excep($e,"product simple add");
		}
	}
	
	public function assignProduct($sku_configurable, $sku_simple, $ukuran){
		
		$ent = array(
			$sku_configurable,
			$sku_simple,
			array('ukuran'),
			array('ukuran' => 'xxs'),
			array(
				'xxs' => array(
					'pricing_value' => 2000,
					'is_percent' => 1
				)
			)
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product_type_configurable.assign', $ent);
			return $res;
		}catch (Exception $e){
			return $e;
			return 0;
			$this->__excep($e,"product assign");
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
			return 0;
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
			return 0;
			$this->__excep($e,"product update stok");
		}
	}
	
	public function assignCat($sku, $categories){		
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_category.assignProduct', array('categoryId' => $categories, 'product' => $sku));
			return $res;
		}catch (Exception $e){
			return 0;
			$this->__excep($e,"product update stok");
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
			return 0;
			$this->__excep($e,"store list");
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
			return 0;
			$this->__excep($e,"store detail");
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
			return 0;
			$this->__excep($e,"category list");
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
			return 0;
			$this->__excep($e,"attribut set list");
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
			return 0;
			$this->__excep($e,"attribut list");
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
			return 0;
			$this->__excep($e,"atribut detail");
		}
	}
	private function __excep($e,$loc){
		echo 'Error on '.$loc.' at '.__FILE__;
		echo '<pre>';
		print_r($e);
		echo '</pre>';
		die();
	}
	//setSpecialPrice
	public function setSpecialPrice($sku,$sprice, $fromdate, $todate){
		
		$ent = array(
			'sku' => $sku,
			'specialPrice' => $sprice, 
			'fromDate' => $fromdate, 
			'toDate' => $todate
		);
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.setSpecialPrice', $ent);
			return $res;
		}catch (Exception $e){
			return 0;
			$this->__excep($e,"product setSpecialPrice");
		}
	}
	
	//getSpecialPrice
	public function getSpecialPrice($sku){
		try {
			$this->__ins = new SoapClient($this->__url);
			$__sess = $this->__ins->login($this->__api,$this->__key);
			$res = $this->__ins->call($__sess, 'catalog_product.getSpecialPrice', $sku);
			return $res;
		}catch (Exception $e){
			return 0;
			$this->__excep($e,"product getSpecialPrice");
		}
	}
}
?>
