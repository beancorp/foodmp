<?php

switch($_GET['cms'])  {

    case 'technology' :
        $smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Technology Talks');
        $smarty -> assign('keywords','Technology Talks');
        $smarty -> assign('itemTitle', $socObj->getTextItemTitle('Technology Talks'));
        $req 	= $socObj -> displayPageFromCMS(13);
        $smarty -> assign('req', $req);
        $content =	$smarty -> fetch('about.tpl');
        $smarty -> assign('content', $content);
        $smarty->assign('is_content',1);
        break;
/*
    case 'facebook':
        $smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Facebook');
        $smarty -> assign('keywords','Technology Talks');
        $smarty -> assign('itemTitle', $socObj->getTextItemTitle('Facebook'));
        $req 	= $socObj -> displayPageFromCMS(100);
        $smarty -> assign('req', $req);
        $content =	$smarty -> fetch('about.tpl');
        $smarty -> assign('content', $content);
        $smarty->assign('is_content',1);
        break;
*/
    default :
        $smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Bobby Allison Testimonial');
        $smarty -> assign('keywords','Bobby Allison Testimonial');
        $smarty -> assign('itemTitle', $socObj->getTextItemTitle('Testimonial'));
        $req 	= $socObj -> displayPageFromCMS(98);
        $smarty -> assign('req', $req);
        $content =	$smarty -> fetch('about.tpl');
        $smarty -> assign('content', $content);
        $smarty->assign('is_content',1);
        break;
        
}

?>
