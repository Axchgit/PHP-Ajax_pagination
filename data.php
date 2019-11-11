<?php
header("Content-Type:text/html;charset=utf-8");
//引入分页类
include "./page.class.php";
//使用PDO连接数据库
try{
	//实例化PDO创建
	$pdo = new PDO("mysql:host=127.0.0.1;dbname=shop;charset=utf8","root","");
	//设置字符集编码
	$pdo->query("set names utf8");
	//SQL预处理语句
	$stmt1 = $pdo->query("select count(*) from `ecs_goods`");
	//实例化分页类对象
	$total = $stmt1->fetchColumn(); 
	$per = 3;
	$page = new Page($total,$per,'./data.php');
	$stmt = $pdo->prepare("select goods_id,goods_name,market_price from ecs_goods limit ".$page->getLimit());
	//获得页码列表信息
	$pagelist = $page->getPageList();
	//执行SQL预处理语句
	$stmt->execute();
}catch(Exception $e){
	echo $e->getMessage().'<br>';
}
//查询结果
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//将分页信息追加到$data数组中
$data[] = $pagelist;
//输出页面
echo json_encode($data);
