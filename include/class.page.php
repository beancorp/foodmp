<?php
class Page  {

	var $page_size;
	var $page_num;
	var $page_this;
	var $page_name;
	var $limit_str;
	var $total;
	
	var $start_str;
	var $prev_str;
	var $next_str;
	var $end_str;
	var $space_str;
	
	var $hidden_no_link;
	
	function page($total,$page_size,$hidden_no_link=true, $page_name= "p"){
		$this -> total = $total;
		if (empty($page_name)){
			$this -> page_name = "p";
		}
		else 
		{
			$this -> page_name = $page_name;
		}
		
		$this -> page_this = $_GET[$this -> page_name] ? $_GET[$this -> page_name] : 1;
		$this -> page_size = $page_size;
		$this -> page_num = ($this->page_size!=0)?ceil ($this -> total/$this->page_size):1; 
		if ( $this->page_this > $this -> page_num ){
			$this->page_this = $this -> page_num;
		}
		$this -> set_str();
		$this -> hidden_no_link = $hidden_no_link;
	}
	
	function set_class_name($class_name){
		$this -> class_name = $class_name;
	}
	
	function set_str($start='<<Start',$prev='<Prev',$next='Next>',$end='End>>',$space="  "){
		$this -> start_str = htmlspecialchars($start);
		$this -> prev_str = htmlspecialchars($prev);
		$this -> next_str = htmlspecialchars($next);
		$this -> end_str = htmlspecialchars($end);
		$this -> space_str = str_replace(' ','&nbsp;',htmlspecialchars($space));
	}

	function get_link($url,$link_num=10,$isajax=false){
		if (($link_num % 2) == 0) $link_num--;
		if ($link_num<3) $link_num=3;
		if(!$isajax){
			strpos($url,'?') ? $url.="&".$this -> page_name."=" : $url.="?".$this -> page_name."=";
		}
		
		$page_offset = floor($link_num/2);
		if ($this->page_num<=$link_num){
			$start_page = 1;
			$end_page = $this->page_num;
		} else {
			$start_page = $this->page_this - $page_offset;
			$end_page = $this->page_this + $page_offset;
			if ( $start_page <1 ) {
				$end_page += abs($start_page) +1 ;
				$start_page = 1;
			}
			if ( $end_page > $this->page_num) {
				$start_page -= ($end_page - $this->page_num);
				$end_page = $this->page_num ;
			}
		}
		$link_str='';
		if ($this -> page_num <2 && $this->hidden_no_link) {
			return;
		}
		if ($this->class_name) {
			$link_class_str = " class=\"{$this->class_name}\"";
			$link_class_startstr = "<span$link_class_str>";
			$link_class_endstr = "</span>";
		} else {
			$link_class_str = "";
			$link_class_startstr = "";
			$link_class_endstr = "";
		}
		if ($this -> page_this != 1) {
			$link_str .= "<a href=\"{$url}".($isajax?"1);":"1")."\"$link_class_str>{$this->start_str}</a>";
		} else {
			if (!$this->hidden_no_link){
				$link_str .= "$link_class_startstr{$this->start_str}$link_class_endstr";
			}
		}
		$link_str .= $this->space_str;
		$prev_page = $this -> page_this -1;
		if ($prev_page >= 1) {
			$link_str .= "<a href=\"{$url}".($isajax?"{$prev_page});":"$prev_page")."\"$link_class_str>{$this->prev_str}</a>";
		} else {
			if (!$this->hidden_no_link){
				$link_str .= "$link_class_startstr{$this->prev_str}$link_class_endstr";
			}
		}
		$link_str .= $this->space_str;
		for ($i=$start_page;$i<=$end_page;$i++){
			$link_str .= $i == $this->page_this ? "$i" : "<a href=\"{$url}".($isajax?"{$i});":"{$i}")."\"$link_class_str>$i</a>";
			$link_str .= $this->space_str;
		}
		$next_page = $this -> page_this +1;
		if ($next_page <= $this->page_num) {
			$link_str .= "<a href=\"{$url}".($isajax?"{$next_page});":"{$next_page}")."\"$link_class_str>{$this->next_str}</a>";
		} else {
			if (!$this->hidden_no_link){
				$link_str .= "$link_class_startstr{$this->next_str}$link_class_endstr";
			}
		}
		$link_str .= $this->space_str;
		if ($this -> page_this != $this->page_num) {
			$link_str .= "<a href=\"{$url}".($isajax?"{$this->page_num});":"{$this->page_num}")."\"$link_class_str>{$this->end_str}</a>";
		} else {
			if (!$this->hidden_no_link){
				$link_str .= "$link_class_startstr{$this->end_str}$link_class_endstr";
			}
		}
		return $link_str;
	}
	
	function get_limit(){
		$start_num = ($this -> page_this-1)*$this->page_size; 
		if ($start_num<0) $start_num=0;
		return ' LIMIT '.$start_num.','.$this->page_size;
	}
	
	function get_results(){
		$start_num = ($this -> page_this-1)*$this->page_size;
		$end_num = $start_num+$this->page_size;
		$total = $this->total;
		if ($end_num>$total) $end_num = $total;
		$start_num++;
		return "Results {$start_num} - {$end_num} of $total";
	}
	
	function set_page_size($page_size){
		$this -> page_size = $page_size;
	}
	
	function set_total($total){
		$this -> total = $total;
	}
}

?>