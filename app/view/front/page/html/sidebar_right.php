<?php if(!isset($page_current)) $page_current = '';?>
<ul class="nav nav-pills">
  <li role="presentation" class="<?php if($page_current == 'col-1') echo 'active';?>">
    <a href="<?=base_url()?>">Example 1 Column</a>
  </li>
  <li role="presentation" class="<?php if($page_current == 'col-2-left') echo 'active';?>">
    <a href="<?=base_url("example/col_2_left")?>">Example 2 Column Left Sidebar</a>
  </li>
  <li role="presentation" class="<?php if($page_current == 'col-2-right') echo 'active';?>">
    <a href="<?=base_url("example/col_2_right")?>">Example 2 Column Right Sidebar</a>
  </li>
  <li role="presentation" class="<?php if($page_current == 'col-3') echo 'active';?>">
    <a href="<?=base_url("example/col_3")?>">Example 3 Column</a>
  </li>
</ul>
