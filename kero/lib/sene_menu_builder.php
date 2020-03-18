<?php
class Sene_Menu_Builder {
	var $treehtml = '';
	var $model;
	public function __construct(){
		$this->treehtml = '';
	}
	private function __treeBuild($id="",$st=0){
		if($st){
			$this->treehtml .= '<ul  class="dropdown">'."\n";
		}else{
			$this->treehtml .= '<ul id="menu-left-menu" class="top-bar-menu left">'."\n";
		}
		$data = $this->model->getChild2($id);
		foreach($data as &$d){
			$this->treehtml .= "\t".'<li id="cat_'.$d->id.'" data-id="'.$d->id.'" data-code="'.$d->code.'" data-slug="'.$d->slug.'" data-category_id="'.$d->category_id.'" data-priority="'.$d->priority.'">'."\n\t\t\t".'<a href="#" class="catinvok">'.$d->name.'</a>'."\n";
			$te = $this->model->getChild2($d->id);
			if(count($te)>0){
				foreach($te as &$t){
					if(count($t)>0){
						$this->treehtml .= "\t\t\t".'<li id="cat_'.$t->id.'" data-id="'.$t->id.'" data-code="'.$t->code.'" data-slug="'.$t->slug.'" data-category_id="'.$t->category_id.'" data-priority="'.$t->priority.'" class="has-dropdown">'."\n\t\t\t".'<a href="#" class="catinvok">'.$t->name.'</a>'."\n";
						$t->child = $this->__treeBuild($t->id,1);
					}else{
						$this->treehtml .= '<li id="cat_'.$t->id.'" data-id="'.$t->id.'" data-code="'.$t->code.'" data-slug="'.$t->slug.'" data-category_id="'.$t->category_id.'" data-priority="'.$t->priority.'"><a href="#" class="catinvok">'.$t->name.'</a>';
					}
					$this->treehtml .= "\t\t\t".'</li>'."\n";
				}
				$this->treehtml .= "\t\t\t".'</ul>'."\n";
			}
			$this->treehtml .= "\t".'</li>'."\n";
			$d->child = $te;
		}
		$this->treehtml .= '</ul>'."\n";
	}
	public function toHtml($datas){
		echo '<ul id="menu-left-menu" class="top-bar-menu left hide-for-medium">';
		foreach($datas as $ds){
			if(count($ds->child)>0){
				echo '<li id="" class="has-dropdown"><a href="'.base_url('kategori/'.$ds->slug).'">'.$ds->name.'</a>';
				$this->uldrop($ds->child);
				echo '</li>';
			}else{
				echo '<li><a href="'.base_url('kategori/'.$ds->slug).'">'.$ds->name.'</a></li>';
			}
		}
		echo '</ul>';
	}
	public function uldrop($datas){
		if(count($datas)>0){
			echo '<ul class="dropdown">';
			foreach($datas as $ds){
				if(count($ds->child)>0){
					echo '<li id="" class="has-dropdown"><a href="'.base_url('kategori/'.$ds->slug).'">'.$ds->name.'</a>';
					$this->uldrop($ds->child);
					echo '</li>';
				}else{
					echo '<li><a href="'.base_url('kategori/'.$ds->slug).'">'.$ds->name.'</a></li>';
				}
			}
			echo '</ul>';
		}
	}
	public function toHtml2($model,$id){
		$this->model = $model;
		if(!empty($id)){
			$this->__treeBuild($id);
		}
	}
}