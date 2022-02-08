<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Профили фондов Сбер Управления Активами");
$APPLICATION->SetPageProperty("description", "Профили фондов Сбер Управления Активами");
$APPLICATION->SetTitle("Профили фондов Сбер Управления Активами");
?>
<style>
	.factsheets {padding:100px;background-color: #fff;box-sizing: border-box;position:relative;min-height:380px}
	.factsheets_title {margin-top: 0px !important;position:relative;z-index:3}
	@media (max-width: 1110px){
		.factsheets {margin-bottom:56px;}
	}
	.info_center > section, .pif_center > section {width: 50%;min-height:200px;float: left;padding-right: 5px;}
	/*.info_center > section > .fund-group > h4 {padding-bottom: 15px;}*/
	.info_center > section > .fund-group > p {line-height: 14px;}
	.new-slider__right {width:auto !important; margin:0 !important}
	@media(max-width:768px){
		.factsheets {padding:0}
		.factsheets_title {padding:25px 15px}
	}
	.header__top-nav, .header__right, .header .mbox, .footer {display: none;}
	.header__top .mbox {display: block;}
	.fund-group .header__subnav-link:hover {color: #000;}
</style>
<div class="disclosure factsheets">
	<section class="info_documents">
		<div class="factsheets_title">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				".default",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => "/factsheets/factsheets_title.php"
				)
			);?>
		</div>
		<div class="new-slider__right">
			<img src="/local/templates/main/img/factsheets_banner3.png"> <img src="/local/templates/main/img/factsheets_banner3_mobile.png">
		</div>
	</section>
</div>
<div class="disclosure">
	<section class="info_documents">
		<div class="factsheets_desc">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				".default",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => "/factsheets/factsheets_desc.php"
				)
			);?>
		</div>
		<div>
			<article class="info_center clearfix">
				<?$APPLICATION->SetPageProperty("sect_id", "2922");
					function MakeElementsTree(){
						global $APPLICATION;
						if(!CModule::IncludeModule("iblock")){
							echo "не подключается модуль инфоблоки";
						}
						// Идентификатор раздела 
						$eltype = $APPLICATION->GetPageProperty("sect_id");
						if (!empty($eltype)){
							//ID инфоблока
							$res = CIBlockSection::GetByID($eltype);
							if($ar_res = $res->GetNext()){
								$parentIBlockID = $ar_res['IBLOCK_ID'];
								$RootSectionName = $ar_res['NAME'];
							}
							$arFilterRoot = array(
								"IBLOCK_ID" => $parentIBlockID,
								"SECTION_ID" => $eltype,
							);
							// массив для хранения корневых элементов
							$ar_rootElements = array();
							$ar_rootElements["NAME"] = $RootSectionName;
							$rootRes = CIBlockElement::GetList(Array(), $arFilterRoot, false);
							while($rootOb = $rootRes->GetNextElement()){
								$RootarFields = $rootOb->GetFields();
								$arRootSelFlds["NAME"] = $RootarFields["NAME"];
								$arRootSelFlds["PREVIEW_TEXT"] = $RootarFields["PREVIEW_TEXT"];
								$arRootSelFlds["DETAIL_PAGE_URL"] = $RootarFields["DETAIL_PAGE_URL"];
								$arRootSelFlds["DETAIL_TEXT_SIZE"] = strlen($RootarFields["DETAIL_TEXT"]);
								$ar_rootElements["ITEMS"][] = $arRootSelFlds;
							}
							$arFilter=array(
								"IBLOCK_ID" => $parentIBlockID,
								"SECTION_ID" => $eltype,
							);
							$ar_result=Array();
							$arProj = CIBlockSection::GetList(array("SORT"=>"ASC"),$arFilter,false);
							while($projRes = $arProj->GetNextElement()){
								$arFields = $projRes->GetFields();
								$ar_result[$arFields["ID"]]["NAME"] = $arFields["NAME"];
							}	
							foreach($ar_result as $arrkey => $arrvalue){	
								$arProjElem = CIBlockElement::GetList(array(),array("SECTION_ID"=>$arrkey),false);
								while($projResElem = $arProjElem->GetNextElement()){
									$arElemFields = $projResElem->GetFields();
									$arSelFlds["NAME"] = $arElemFields["NAME"];
									$arSelFlds["PREVIEW_TEXT"] = $arElemFields["PREVIEW_TEXT"];
									$arSelFlds["DETAIL_PAGE_URL"] = $arElemFields["DETAIL_PAGE_URL"];
									$arSelFlds["DETAIL_TEXT_SIZE"] = strlen($arElemFields["DETAIL_TEXT"]);
									$ar_result[$arrkey]["ITEMS"][] = $arSelFlds;
								}
							}
							if(isset($ar_rootElements["ITEMS"]) && count($ar_rootElements["ITEMS"]) > 0){
								echo "<div class=\"header__subnav-title\">".$ar_rootElements["NAME"]."</div>";
								echo "<div class=\"hr\"></div>";
								foreach($ar_rootElements["ITEMS"] as $ar_rootItem){
									//echo "<p>";
									if($ar_rootItem["DETAIL_TEXT_SIZE"] > 0){
										echo "<a href=\"".$ar_rootItem["DETAIL_PAGE_URL"]."\" target=\"_blank\" class=\"header__subnav-link\">".$ar_rootItem["NAME"]."</a>";
									} else {
										echo $ar_rootItem["NAME"];
									}
									if(strlen($ar_rootItem["PREVIEW_TEXT"]) > 0){
										echo "<span>".$ar_rootItem["PREVIEW_TEXT"]."</span>"; 
									}
									//echo "</p>";
								}
							}
							foreach($ar_result as $key => $arrValues){					
								echo "<section>";
									echo "<div class=\"fund-group\">";
										echo "<div class=\"header__subnav-title\">".$arrValues["NAME"]."</div>";
										echo "<div class=\"hr\"></div>";
										if(is_array($arrValues["ITEMS"]) && count($arrValues["ITEMS"]) > 0) {
											foreach ($arrValues["ITEMS"] as $arrItem){
												//echo "<p>";
												//print_r($arrItem);
												if($arrItem["DETAIL_PAGE_URL"]) {
													echo "<a href=\"".$arrItem["DETAIL_PAGE_URL"]."\" target=\"_blank\" class=\"header__subnav-link\">".$arrItem["NAME"]."</a>";
												} else {
													 echo $arrItem["NAME"];
													}
												if(strlen($arrItem["PREVIEW_TEXT"]) > 0) {
													echo "<span>".$arrItem["PREVIEW_TEXT"]."</span>"; 
												}
												//echo "</p>";
											}
										}
									echo "</div>";
								echo "</section>";
							}
						} else {
							showError("В свойствах страницы не указан ID раздела с элементами");
						}
					} 
				?> 
				<?MakeElementsTree();?> 
			</article>
		</div>
	</section>
</div>
<!-- закрываем следующим div-ом верстку, чтобы футер был по ширине страницы а не в блоке main_cont -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-business-days/1.2.0/index.min.js"></script>
<script>
	$(document).ready(function(){
		
		// Последний календарный день предыдущего месяца
		// var today = moment().subtract(1, 'month').endOf('month').format('DD.MM.YYYY');
		// var dateThis = "Профили фондов по состоянию на " + today;
		// //console.log(dateThis);
		// $('p.setTextDate').html(dateThis);

		// Последний рабочий день предыдущего месяца
		var today = moment().subtract(1, 'month').endOf('month')
		var dateThis = moment(today, 'DD-MM-YYYY').prevBusinessDay().format('DD.MM.YYYY')
		//console.log(dateThis)
		$('p.setTextDate').html("Профили фондов по состоянию на " + dateThis)

	});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>