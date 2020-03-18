<?php
abstract class SENE_Model{
	protected $koneksi;
	private $fieldname = array();
	private $fieldvalue = array();

	function __construct(){
		$this->koneksi=koneksi_db();
	}
	public function last_id(){
		return mysql_insert_id($this->koneksi);
	}
	public function filter(&$str){
		$str=filter_var($str,FILTER_SANITIZE_SPECIAL_CHARS);
	}
	protected function exec($sql){
		$res = mysql_query($sql,$this->koneksi);
		if($res){
			return 1;
		}else{
			$this->fieldname[] = 'error';
			$this->fieldname[] = 'code';
			$this->fieldname[] = 'sql';
			$this->fieldvalue[] = mysql_error($this->koneksi);
			$this->fieldvalue[] = mysql_errno($this->koneksi);
			$this->fieldvalue[] = $sql;
			return 0;
		}
	}
	protected function select($sql){
		$res = mysql_query($sql,$this->koneksi);
		if($res){
			$dataz=array();
			while($data=mysql_fetch_assoc($res)){
				array_push($dataz,$data);
			}
			return $dataz;
		}else{
			$this->fieldname[] = 'error';
			$this->fieldname[] = 'code';
			$this->fieldname[] = 'sql';
			$this->fieldvalue[] = mysql_error($this->koneksi);
			$this->fieldvalue[] = mysql_errno($this->koneksi);
			$this->fieldvalue[] = $sql;
			return $this->fieldvalue;
		}
	}
	public function getStat(){
		return array("fieldname"=>$this->fieldname,"fieldvalue"=>$this->fieldvalue);
	}
	public function prettyName($name){
		$name=strtolower(trim($name));
		$names=explode("_", $name);
		$name='';
		foreach($names as $n){
			$name=$name.''.ucfirst($n).' ';
		}
		return $name;
	}
	public function formContent($name,$type="text",$value="",$req=0){
		echo '<div id="row">';
		if($type=="file"){
			echo '<label class="input-control file span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="file" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'" />';
		}elseif($type=="datepicker"){
			echo '<label class="input-control text>';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '</label>&nbsp;</div>';
			if($value==""){
				echo '<label class="input-control text datepicker span4" for="i'.$name.'" data-role="datepicker" data-param-year-buttons="1">';
			}else{
				echo '<label class="input-control text datepicker span4" for="i'.$name.'" data-param-init-date="'.$value.'" data-param-year-buttons="1">';
			}
			echo '<input type="text" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" />';
		}elseif($type=="datetime"){
			echo '<label class="input-control span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="text" id="i'.$name.'" name="'.$name.'" />';
			?>
			<script type="text/javascript">
					$(function(){
							$('#i<?php echo $name; ?>').appendDtpicker({
									'dateFormat' : 'YYYY-MM-DD hh:mm'
									<?php if($value!='') echo ", 'current' : '".$value."'"; ?>
							});
					});
			</script>
			<?php
		}elseif($type=="date"){
			echo '<label class="input-control span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="text" id="i'.$name.'" name="'.$name.'" />';
			?>
			<script type="text/javascript">
					$(function(){
							$('#i<?php echo $name; ?>').appendDtpicker({
									'dateFormat' : 'YYYY-MM-DD'
									<?php if($value!='') echo ", 'current' : '".$value."'"; ?>
							});
					});
			</script>
			<?php
		}elseif($type=="time"){
			echo '<label class="input-control span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="text" id="i'.$name.'" name="'.$name.'" />';
			?>
			<script type="text/javascript">
					$(function(){
							$('#i<?php echo $name; ?>').appendDtpicker({
									'dateFormat' : 'hh:mm'
									<?php if($value!='') echo ", 'current' : '".$value."'"; ?>
							});
					});
			</script>
			<?php
		}elseif($type=="textarea"){
			echo '<label class="input-control textarea span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<textarea id="i'.$name.'" name="'.$name.'">'.$value.'</textarea>';
		}elseif($type=="switch"){
			echo '<label class="input-control switch span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="checkbox" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'" />';
		}elseif($type=="switchAlt"){
			echo '<label class="input-control select span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<select id="i'.$name.'" name="'.$name.'">';
			echo '<option value="1"';
			if($value==1) echo ' selected="selected" ';
			echo '>yes</value>';
			echo '<option value="0"';
			if($value==0) echo ' selected="selected" ';
			echo '>no</value>';
			echo '</select>';
		}else{
			echo '<label class="input-control span4" for="i'.$name.'">';
			echo '<span class="helper">'.$this->prettyName($name).'</span>';
			echo '<input type="text" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'"';
			if($req) echo ' required="required" ';
			echo ' />';
		}
		echo '</label>&nbsp;</div>';
	}
}
