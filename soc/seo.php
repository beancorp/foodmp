<?php
if(!isset($custom_seo_keywords) || !$custom_seo_keywords) {
  $smarty -> assign('keywords','soc exchange australia, sock exchange, auction, antiques, books, cars, motorbikes, pottery, clothing, computers, electronics, dolls, home, jewellery, jewelry, music, sports, toys, sydney, melbourne, brisbane, perth, adelaide, tasmania');
}
if (!$is_set_desc) {
	$smarty -> assign('description', 'Buy & Sell on SOC Exchange Australia for only $1/month. List Jobs, Properties and Cars too all for $1/month!');
}

?>