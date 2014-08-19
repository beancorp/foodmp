<?php
	class gallery extends common {
		var $gallerylc;
		var $dbcon  = null;
		var $table  = '';
		var $smarty = null;
		var $lang   = null;
		
		public function __construct(){
			/**
			 * The wishlist image file location:
			 * The wishlist mp3 file location: 
			**/
			$this->gallerylc = "/upload/wishlist/image";
			$this->dbcon  = &$GLOBALS['dbcon'];
			$this->table  = &$GLOBALS['table'];
			$this->smarty = &$GLOBALS['smarty'];
			$this->lang   = &$GLOBALS['_LANG'];
		}
		public function __destruct(){
			unset( $this->dbcon, $this->table, $this->smarty, $this -> lang , $this->imagelc, $this->mp3lc);
		}
		/*** save some thing for gallery**/
		public function SaveGallery($data){
			$this->dbcon->insert_record($this->table."gallery_images", $data);
			return $this->dbcon->insert_id();
		}
		public function SaveGalleryCategory($data){
			$this->dbcon->insert_record($this->table."gallery_category", $data);
			return $this->dbcon->insert_id();
		}
        public function importGallery($images, $lastOrder){
            $imgup = new uploadImages();
            $field = $products[0];
            $images = $imgup->getzipProductMoreImages($images);
            $sortImages = array();
            foreach($images as $image){
                $sortImages[$image['tmpname']] = $image;
            }
            ksort($sortImages);

            $uploadF = new uploadFile();
            foreach($sortImages as $name=>$image){
                $thumbsImage = '/upload/wishlist/gallery_photo/'.basename($image['smallpicture']);
                $bigImage = '/upload/wishlist/gallery_photo/'.basename($image['picture']);
                $uploadF->MakeImage(BP.$image['smallpicture'], BP.$thumbsImage, 100, 100, true, 100, TRUE);
                $uploadF->MakeImage(BP.$image['picture'], BP.$bigImage, 480, 320, true, 100, TRUE);
                @unlink(BP.$image['smallpicture']);
                @unlink(BP.$image['picture']);
                $aryResult = array();
                $aryResult['gallery_category'] = $_POST['gallery_category'];
                $aryResult['gallery_thumbs'] = $thumbsImage;
                $aryResult['gallery_images'] = $bigImage;
                $aryResult['gallery_desc'] = '';
                $aryResult['StoreID'] = $_SESSION['ShopID'];
                $aryResult['gallery_order'] = $lastOrder;
                $aryResult['gallery_addtime'] = time();
                $aryResult['gallery_lastupdate'] = time();
                $this->SaveGallery($aryResult);
                $lastOrder++;
            }

        }
		/**end save**/
		
		/**update some thing for gallery **/
		public function updateGallery($data){
			return $this->dbcon->update_record($this->table."gallery_images",$data," where id={$data['id']} ");
		}
		public function updateGalleryCategory($data){
			return $this->dbcon->update_record($this->table."gallery_category",$data," where id={$data['id']} ");
		}
		/** end update**/
		
		/**Delete function for the gallery**/
		public function DeleteGalleryCategory($StoreID,$id){
			$result = $this->getGalleryCategoryInfo($StoreID,$id);
			$query = "Delete from {$this->table}gallery_category where id='$id'";
			if($this->dbcon->execute_query($query)){
				@unlink(ROOT_PATH.$result['gallery_category_thumbs']);
				@unlink(ROOT_PATH.$result['gallery_category_images']);
				$results = $this->getgalleryListByCategory($id);
				if($results){
					foreach ($results as $pass){
						$this->DeleteGalleryInfo($StoreID,$pass['id']);
					}
				}
				return true;
			}
		}
		
		public function DeleteGalleryInfo($StoreID,$id){
			$result = $this->galleryInfo($StoreID,$id);
			
			$query = "update {$this->table}gallery_images set gallery_order=gallery_order-1"
					." where gallery_order > {$result['gallery_order']} and "
					."gallery_category={$result['gallery_category']}";
			$this->dbcon->execute_query($query);
			$query = "Delete from {$this->table}gallery_images where id='$id'";
			if($this->dbcon->execute_query($query)){
				@unlink(ROOT_PATH.$result['gallery_thumbs']);
				@unlink(ROOT_PATH.$result['gallery_images']);
				return true;
			}
			return false;
		}
		/**end delete**/
		
		/**gallery category list**/
		public function getGalleryCategory($StoreID){
			$query = "SELECT * FROM {$this->table}gallery_category where StoreID='{$StoreID}'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				for ($i=0;$i<count($result);$i++){
					$query = "SELECT * FROM {$this->table}gallery_images where gallery_category='{$result[$i]['id']}' order by gallery_addtime DESC limit 1";
					$this->dbcon->execute_query($query);
					$firstimg = $this->dbcon->fetch_records(true);
					if($firstimg){
						$result[$i]['gallery_category_thumbs'] = $firstimg[0]['gallery_thumbs'];
					}else{
						$result[$i]['gallery_category_thumbs'] = "";
					}
				}
			}
			return $result;
		}
		/**end list**/
		/**gallery category info**/
		public function getGalleryCategoryInfo($StoreID,$id){
			$query = "SELECT * FROM {$this->table}gallery_category where StoreID='{$StoreID}' and id='$id'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$query = "SELECT * FROM {$this->table}gallery_images where gallery_category='$id' order by gallery_addtime DESC limit 1";
			$this->dbcon->execute_query($query);
			$firstimg = $this->dbcon->fetch_records(true);
			if($firstimg){
				$result[0]['gallery_category_thumbs'] = $firstimg[0]['gallery_thumbs'];
			}else{
				$result[0]['gallery_category_thumbs'] = "";
			}
			return @$result[0];
		}
		/**end gallery info**/
		
		/** gallery list**/
		public function gallerylist($StoreID,$cid,$pageid=0){
			$query = "SELECT * FROM {$this->table}gallery_images where StoreID='{$StoreID}' "
					."and gallery_category='$cid' order by gallery_order asc,gallery_addtime DESC";
			if($pageid){
				$query .= " limit ".($pageid-1)*18 .",18 ";
			}
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		public function gallerylistPages($StoreID,$cid,$curpage){
                        require_once dirname(__FILE__).'/class/pagerclass.php';
                        $obj_page=new pagerClass();
			$pageno		=	$curpage >0 ? $curpage : 1;
			$perPage	=	18;
			$arrResult = array();
			$query = "SELECT count(*) as num FROM {$this->table}gallery_images where StoreID='{$StoreID}' and gallery_category='$cid'";
			$this->dbcon->execute_query($query);
			$totalNum = $this->dbcon->fetch_records(true);
			
			$totalNum	= 	$totalNum[0]['num'];
			($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
			$start	= ($pageno-1) * $perPage;
			$site = $_REQUEST['site'];
			$gallery_name = $_REQUEST['gallery'];
			if($_REQUEST['act']=='galleryInt'){
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => '',
				'append'     => true,
				'urlVar'     => 'l=0&p',
				'path'		 => '',
				'fileName'   => '%d',
				);
			}else{
				$params = array(
					'perPage'    => $perPage,
					'totalItems' => $totalNum,
					'currentPage'=> $pageno,
					'delta'      => 15,
					'onclick'	 => '',
					'append'     => false,
					'urlVar'     => 'l=0&p',
					'path'		 => '',
					'fileName'   => '/'.$site.'/gallery/'.$gallery_name.'/%d/0',
					);
			}
			$pager = Pager::factory($params);
			//$arrResult['links'] 		= $pager->getLinks();
                        if($_REQUEST['act']=='galleryInt'){
                            $arrResult['links'] 		= $pager->getLinks();
                        }
                        else {
                            $arrResult['links'] 		= $obj_page->getLinkAjax($pageno,$totalNum,$perPage,'getGallery(this.href,false);');
                        }
			$arrResult['links']['totals'] = $totalNum;
			return $arrResult;
		}
		/** end gallery list**/
		
		/** gallery info by id**/
		public function galleryInfo($StoreID,$id){
			$query = "SELECT * FROM {$this->table}gallery_images where StoreID='{$StoreID}' and id='$id'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			return @$result[0];
		}
		/** end gallery info**/
		
		/** get gallery list by category id**/
		public function getgalleryListByCategory($cid){
			$query = "SELECT * FROM {$this->table}gallery_images where gallery_category='$cid'";
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		
		/**get userlist by storeID and send invations time.**/
		public function getuserlist($StoreID,$times){
			$query = "SELECT invitation_name,invitation_email  FROM {$this->table}wishlist_invation where add_time in($times) and StoreID='$StoreID' group by invitation_email";
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		
		public function geteventlist($StoreID){
			$query = "SELECT * FROM {$this->table}wishlist_invation where StoreID='$StoreID' group by add_time";
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		
		public function addsendemaillog($data){
			$this->dbcon->insert_record($this->table."gallery_email_logs", $data);
			return $this->dbcon->insert_id();
		}
		public function getusertempl($StoreID){
			$query = "SELECT * FROM {$this->table}gallery_template WHERE StoreID in (0,$StoreID) order by sort";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			$aryBanner = array();
			if($result){
				foreach ($result as $pass){
					if($pass['StoreID']==0){
						$aryBanner['SYSTEM'][$pass['b_type']][$pass['id']] = $pass; 
					}else{
						$aryBanner['USER'] = $pass;
					}
				}
			}
			return $aryBanner;
		}
		
		public function getGalleryTemplate($banner){
			$query = "SELECT * FROM {$this->table}gallery_template WHERE id='$banner'";
			$this->dbcon->execute_query($query);
			if($result = $this->dbcon->fetch_records(true)){
				return $result[0];
			}
			return false;
		}
		
		public function saveUserTempl($StoreID,$template,$banner){
			$query = "SELECT * FROM {$this->table}gallery_template where StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				$query = "UPDATE {$this->table}gallery_template SET template='$template',banner='$banner'  WHERE StoreID='$StoreID'";
				$this->dbcon->execute_query($query);
				$tplid = $result['0']['id'];
			}else{
				$query = "INSERT INTO {$this->table}gallery_template(`template`,`banner`,`flash_banner`,`type`,`thumbimg`,`bigimage`,`StoreID`)values('$template','$banner','','image','','','$StoreID')";
				$this->dbcon->execute_query($query);
				$tplid = 	$this->dbcon->insert_id();			
			}
			
			return $tplid;
		}
	
		public function getFirstImages($StoreID,$cid,$pageid=0){
			$query = "SELECT * FROM {$this->table}gallery_images where StoreID='{$StoreID}' and gallery_category='$cid' order by gallery_addtime DESC";
			if($pageid){
				$query .= " limit ".($pageid-1)*18 .",18 ";
			}
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				return "#".$result[0]['gallery_images'];
			}
			return "";
		}
	
		public function getGalleryByName($name,$StoreID){
			$query = "SELECT * FROM {$this->table}gallery_category where gallery_url='$name' and StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		
		public function checkgalleryURL($gallery_name,$StoreID,$id=0){
			$query = "SELECT count(*) as num FROM {$this->table}gallery_category where gallery_url='".clean_url_name($gallery_name)."' and StoreID='$StoreID'";
			if($id!=0){
				$query .= " and id!='$id'";
			}
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result[0]['num']>0){
				return false;
			}
			return true;
		}
		
		public function getGalleryEmailLog($StoreID,$cid,$curpage=1){
			global $dbcon;
			$pageno		=	$curpage >0 ? $curpage : 1;
			$perPage	=	18;
			$arrResult = array();
			$sql = "SELECT count(*) as num from {$this->table}gallery_email_logs where StoreID='$StoreID' and categoryid='$cid'";
			$dbcon->execute_query($sql);
			$totalNum	=	$this->dbcon->fetch_records();
			$totalNum	= 	$totalNum[0]['num'];
			($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
			$start	= ($pageno-1) * $perPage;
			$sql = "SELECT * FROM {$this->table}gallery_email_logs where StoreID='$StoreID' and categoryid='$cid' order by addtime DESC limit $start,$perPage";
			$dbcon->execute_query($sql);
			$arrResult['invitation_list']=$dbcon->fetch_records('true');
			$params = array(
					'perPage'    => $perPage,
					'totalItems' => $totalNum,
					'currentPage'=> $pageno,
					'delta'      => 15,
					'onclick'	 => 'javascript:xajax_galleryEmailList(\'%d\',\''.$StoreID.'\',\''.$cid.'\');return false;',
					'append'     => false,
					'urlVar'     => 'pageno',
					'path'		 => '#',
					'fileName'   => '%d',
					);
			$pager = Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			return $arrResult;
		}
		/*
		 * Generate the order number of image in gallery
		 * @input: String gallery_category, int order, int id
		 * @output: int order
		 * @desc: add by jessee 20100524
		 */
		public function generateOrder($category, $order, $id=0){
			$sql = "select count(*) as num from ".$this->table."gallery_images where gallery_category=$category order by gallery_order asc";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records();
			$total = $result[0]['num'];
			$oldOrder = 0;
			if ($id > 0){
				$sql = "select gallery_order from ".$this->table."gallery_images where id=$id";
				$this->dbcon->execute_query($sql);
				$result = $this->dbcon->fetch_records();
				$oldOrder = $result[0]['gallery_order'];
			}
			if ($total < $order){
				return ($id>0)?$total:$total+1;
			}elseif($order <= 1){
				if ($id > 0){
					if ($oldOrder > 1){
						$sql = "update ".$this->table."gallery_images set gallery_order=gallery_order+1 "
							."where gallery_category='$category' and gallery_order>=1 and "
							."gallery_order<$oldOrder and id!=$id";
					}else{
						return 1;
					}
				}else{
					$sql = "update ".$this->table."gallery_images set gallery_order=gallery_order+1 ";
					$sql.= "where gallery_category='$category' and gallery_order>=1";
				}
				$this->dbcon->execute_query($sql);
				return 1;
			}else{
				if ($id > 0){
					if ($oldOrder == $order){
						return $order;
					}elseif ($oldOrder > $order){
						$sql = "update ".$this->table."gallery_images set gallery_order=gallery_order+1 ";
						$sql.= "where gallery_category='$category' and id!=$id";
						$sql.= " and gallery_order>=$order and gallery_order<$oldOrder";
					}elseif ($oldOrder < $order){
						$sql = "update ".$this->table."gallery_images set gallery_order=gallery_order-1 ";
						$sql.= "where gallery_category='$category' and id!=$id";
						$sql.= " and gallery_order>$oldOrder and gallery_order<=$order";
					}
				}else{
					$sql = "update ".$this->table."gallery_images set gallery_order=gallery_order+1 ";
					$sql.= "where gallery_category='$category' and gallery_order>='$order' ";
				}
				$this->dbcon->execute_query($sql);
				return $order;
			}
		}

        /**
         * Get last image order by category
         * @global <class.db> $dbcon
         * @param <int> $storeID
         * @param <int> $cid
         * @return <int> last image order
         * @author ronald
         */
        public function getLastOrder($storeID,$cid){
            global $dbcon;
			$sql = "SELECT gallery_order FROM {$this->table}gallery_images where StoreID='$storeID' and gallery_category='$cid' order by gallery_order DESC limit 1";
			$dbcon->execute_query($sql);
			$arrResult =$dbcon->fetch_records('true');
			if(!empty($arrResult)){
                return $arrResult[0]['gallery_order'];
            }
            return 0;
        }
	}
?>