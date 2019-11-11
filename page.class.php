<?php
class page{
	private $total;	//总页数
	private $size;	//每页记录数
	private $url;	//URL地址
	private $page;	//当前页码
	/**
	 * 构造函数
	 * @param $total 总记录数
	 * @param $size  每页记录数
	 * @param $url   URL地址
	 */
	public function __construct($total,$size,$url=''){
		//计算页数，向上取整
		$this->total = ceil($total / $size);
		//获得当前页码
		$this->page = $this->getNowPage();
		//每页记录数
		$this->size = $size;
		//为URL添加GET参数
		$this->url = $this->setUrl($url);
	}
	/**
	 * 获得当前页码
	 */
	private function getNowPage(){
		$page = empty($_GET['page']) ? 1 : $_GET['page'];
		if($page < 1){
			$page = 1;
		}else if($page > $this->total){
			$page = $this->total;
		}
		return $page;
	}
	/**
	 * 为URL添加GET参数，去掉page参数
	 */
	private function setUrl($file){
		$params = $_GET;
		unset($params['page']);
		$url = http_build_query($params);
		return $url ? "$file?$url&" : "$file?";
	}
	/**
	 * 获得分页导航
	 */
	public function getPageList(){
		//总页数不超过1时直接返回空结果
		if($this->total<=1){
			return '';
		}
		//拼接分页导航的HTML
		$html = '';
		if($this->page>4){
			$html = "<a href=\"#\" onclick=\"getGoods('{$this->url}page=1')\">1</a> ... ";
		}
		for($i=$this->page-3,$len=$this->page+3; $i<=$len && $i<=$this->total; $i++){
			if($i>0){
				if($i==$this->page){
					$html .= " <a href=\"#\" onclick=\"getGoods('{$this->url}page=$i')\" class=\"curr\">$i</a>";
				}else{ 
					$html .= " <a href=\"#\" onclick=\"getGoods('{$this->url}page=$i')\">$i</a> ";
				}
			}
		}
		if($this->page+3<$this->total){
			$html .= " ... <a href=\"#\" onclick=\"getGoods('{$this->url}page={$this->total}')\">{$this->total}</a>";
		}
		//返回拼接结果
		return $html;
	}
	/**
	 * 获得 SQL中的 limit
	 */
	 public function getLimit(){
		if($this->total==0){
			return '0, 0';
		}
		return ($this->page - 1) * $this->size . ", {$this->size}";
	 }
}
