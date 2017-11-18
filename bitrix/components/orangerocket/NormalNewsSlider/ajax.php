<?php  require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if (CModule::IncludeModule("sale")  && CModule::IncludeModule("catalog") ){
	$PRODUCT_ID=$_POST['itemId'];
	$ParentId=$_POST['parentId'];
	$discountValue=$_POST['discountValue'];
	$aDiscountFields = array(
		"LID"=>"s1",
		"ACTIVE"=>"Y",
		"SORT"=>100,
		"USER_GROUPS"=>array(0=>2),
		"CONDITIONS"=> array
		(
			"CLASS_ID" => "CondGroup",
			"DATA" => array
			(
				"All" => "AND",
				"True" => "True",
			),

			"CHILDREN" => array
			(
				0 => array
				(
					"CLASS_ID" => "CondBsktProductGroup",
					"DATA" => array
					(
						"Found" => "Found",
						"All" => "AND"
					),

					"CHILDREN" => array
					(
						1 => array
						(
							"CLASS_ID" => "CondBsktFldProduct",
							"DATA" => array
							(
								"logic" => "Equal",
								"value" => $ParentId,
							)

						)

					)

				)

			)

		),
		"ACTIONS"=>
		array
		(
			"CLASS_ID" => "CondGroup",
			"DATA" => array
			(
				"All" => "AND"
			),

			"CHILDREN" => array
			(
				0 => array
				(
					"CLASS_ID" => "ActSaleBsktGrp",
					"DATA" => array
					(
						"Type" => "Discount",
						"Value" => $discountValue,
						"Unit" => "CurAll",
						"All" => "AND",
					),

					"CHILDREN" => array
					(
						0 => array
						(
							"CLASS_ID" => "CondBsktFldProduct",
							"DATA" => array
							(
								"logic" => Equal,
								"value" => $PRODUCT_ID
							)

						)

					)

				)

			)

		)
	);


	if( !($iCatalogDiscount = CSaleDiscount::Add( $aDiscountFields ) ) ) {
		/**
		 * Выводим текст ошибки
		 */
		echo $APPLICATION->GetException()->GetString();
	}


	$PRODUCT_ID=$_POST['itemId'];
	$QUANTITY=1;
	Add2BasketByProductID(
		$PRODUCT_ID,
		$QUANTITY,
		array(),
		array()
	);

 if($cntBasketItems = CSaleBasket::GetList(
   array(),
   array( 
      "FUSER_ID" => CSaleBasket::GetBasketUserID(),
      "LID" => SITE_ID,
      "ORDER_ID" => "NULL",
      "PRODUCT_ID"=>$ParentId
   ), 
   array()
)==0){
	Add2BasketByProductID(
		$ParentId,
		$QUANTITY,
		array(),
		array()
	);

}}
?>