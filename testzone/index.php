<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("sale")&&CModule::IncludeModule("highloadblock"))or die ('error on include modules')){
    $url= $_SERVER['REQUEST_URI'];
    $parseUrl=parse_url($url);
    $url_path=$parseUrl['path'];
    if(strpos($url_path,'.json')){
        header('Content-Type: application/json');
        if(strpos($url_path,'testzone/catreplace.json')){
            /////////////////////////////////////////////////CATREPLACE///////////////==categories and vendors==
            $cat_replace='';

            require_once('cat_replace.php');
            return;
        }elseif(strpos($url_path,'testzone/potok.json')){
            /////////////////////////////////////////////////POTOK////////////////////==categories and vendors==
            $cat_replace='';

            require_once('cat_replace.php');
            return;
            //$vendors_disabled=true;
        }elseif(preg_match("/testzone\/(?P<id>.+?)\/(?P<json>\%.+\D)\/(?P<limit>.+?)\/(?P<desc_size>.+?)\/catreplace\.json/", $url_path, $matches)){
            /////////////////////////////////////////////////CATREPLACE///////////////==products==
            //print_r($matches);
            $cat_replace=$matches['id'];
			
            $json=urldecode($matches['json']);
			$json=json_decode($json,true);
			$search=$json['search'];
			$vendor=$json['vendor'];
            $quant=$json['quant'];
			
            $limit=$matches['limit'];
            $desc_size=$matches['desc_size'];
            if(empty($cat_replace))$cat_replace=1;
            if(empty($limit))$limit=1;
            if(empty($desc_size))$desc_size=1;

            require_once('cat_replace.php');
            return;
        }elseif(preg_match("/testzone\/(?P<json>\{.+\})\/potok\.json/", $url_path, $matches)){
            /////////////////////////////////////////////////POTOK////////////////////==categories and props==
            $json=urldecode($matches['json']);
            $json=json_decode($json,true);
            $noprop=$json['noprop'];
            $section_id=$json['section_id'];
            $print=$json['print'];

            require_once('sections.php');
            return;
        }elseif(strpos($url_path,'testzone/catreplace_update.json')){
            /////////////////////////////////////////////////CATREPLACE///////////////==update==
            if(isset($_REQUEST['json'])){
                require_once('json_update.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/wp-terminal.json')){
            if(isset($_REQUEST['json'])){
                $json = json_decode($_REQUEST['json'],true);
                $action = '';
                if(isset($json['img_url'])) $action = 'image';
                elseif(isset($json[0]['wp_id'])) $action = 'wp_id';
                
                require_once('util/wp.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/sms.json')){
            if(isset($_REQUEST['json'])){
                $json = json_decode($_REQUEST['json'],true);
                require_once('util/order_sms.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/import.json')){
            /////////////////////////////////////////////////IMPORT///////////////////
            if(isset($_REQUEST['json'])){
                require_once('json_import.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/red_list.json')){
            /////////////////////////////////////////////////REDLIST//////////////////
            require_once('json_red_list.php');
            return;
        }elseif(preg_match("/testzone\/(?P<json>\{.+\})\/rules\.json/", $url_path, $matches)){
            /////////////////////////////////////////////////RULES////////////////////
            $json=urldecode($matches['json']);
            $json=json_decode($json,true);

            $action=$json['action'];

            require_once('rules.php');
            return;
        }elseif(strpos($url_path,'testzone/rules.json')){
            /////////////////////////////////////////////////RULES///////////////////
            if(isset($_REQUEST['json'])){
                $json=json_decode($_REQUEST['json'],true);

                $action=$json['action'];
                require_once('rules.php');
            }
            return;
        }elseif(preg_match("/testzone\/(?P<json>\{.+\})\/elza\.json/", $url_path, $matches)){
            /////////////////////////////////////////////////ELZA////////////////////
            $json=urldecode($matches['json']);
            $json=json_decode($json,true);

            $action=$json['action'];

            require_once('util/elza.php');
            return;
        }elseif(strpos($url_path,'testzone/elza.json')){
            /////////////////////////////////////////////////ELZA///////////////////
            if(isset($_REQUEST['json'])){
                $json=json_decode($_REQUEST['json'],true);

                $action=$json['action'];
                require_once('util/elza.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/infos.json')){
            /////////////////////////////////////////////////INFOS///////////////
            if(isset($_REQUEST['json'])){
                $json=urldecode($_REQUEST['json']);
                $json=json_decode($json,true);
                require_once('util/infos.php');
            }
            return;
        }elseif(strpos($url_path,'testzone/sets.json')){
            /////////////////////////////////////////////////SETS////////////////
            require_once('util/sets.php');
            /*if(isset($_REQUEST['json'])){
                $json=urldecode($_REQUEST['json']);
                $json=json_decode($json,true);
                require_once('util/sets.php');
            }*/
            return;
        }
    }
        echo json_encode($url_path);
}
