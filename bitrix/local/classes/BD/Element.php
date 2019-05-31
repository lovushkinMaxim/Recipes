<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 19.11.2018
 * Time: 19:24
 */

namespace BD;
\CModule::IncludeModule('iblock');
class Element{
    protected $iblockCode;
    protected $iBlock;
    protected $defaultSelect = [
        "ID",
        "ACTIVE",
        "IBLOCK_ID",
        "NAME",
        "PREVIEW_TEXT",
        "PREVIEW_PICTURE",
        "DETAIL_TEXT",
    ];
    protected $defaultFilter = [
        "ACTIVE" => 'Y',
    ];

    function __construct() {
        if (!$this->iblockCode)return;
        $this->iBlock = $this->getIBlock();
    }

    function getIBlock(){
        $iBlock_res = \CIBlock::GetList(
            Array(),
            Array(
                'CODE'=>$this->iblockCode,
                'ACTIVE'=>'Y',
            ), true
        );
       return $iBlock_res->GetNext();
    }

    function getList($params){

        $arSelect = array_merge($this->defaultSelect,$params['select']?$params['select']:[]);
        $arSelect = array_unique($arSelect);

        $arFilter = array_merge($this->defaultFilter,$params['filter']?$params['filter']:[]);

        if ($this->iBlock){
            $arFilter["IBLOCK_TYPE"]=$this->iBlock['IBLOCK_TYPE_ID'];
            $arFilter["IBLOCK_ID"]=$this->iBlock['ID'];
        }

        $arNav = false;
        if ($params['limit']){
            $arNav['nTopCount'] = $params['limit'];
        }

        $result = [];
        $bdRes = \CIBlockElement::GetList($params['sort'], $arFilter,false,$arNav,$arSelect);
        while($ob = $bdRes->GetNextElement()) {
            $props = $ob->GetProperties();
            $arFields = $ob->GetFields();
            foreach ($props as $prop){
                $arFields[$prop["CODE"]] = $prop['VALUE'];
                $arFields[$prop["CODE"].'_ID'] = $prop['PROPERTY_VALUE_ID'];
            }
            $result[$arFields['ID']] = $arFields;
        }
        return $result;
    }

    public function getRow(array $params = [])
    {
        $params["limit"] = 1;
        $result = $this->getList($params);

        return is_array($result)
            ? array_shift($result)
            : false;
    }

    function getSections($params){
        $defaultSelect = [
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DESCRIPTION",
            "PICTURE",
        ];

        $arSelect = array_merge($defaultSelect,$params['select']?$params['select']:[]);
        $arSelect = array_unique($arSelect);

        $arFilter = $params['filter']?$params['filter']:[];

        if ($this->iBlock){
            $arFilter["IBLOCK_ID"]=$this->iBlock['ID'];
        }
        $arFilter["GLOBAL_ACTIVE"]=isset($arFilter["GLOBAL_ACTIVE"])?$arFilter["GLOBAL_ACTIVE"]:'Y';

        $sections = [];

        $db_list = \CIBlockSection::GetList($arSelect, $arFilter, true);

        while($ar_result = $db_list->GetNext())
        {
            if ($ar_result['ELEMENT_CNT']){
                $sections[] = $ar_result;
            }
        }

        return $sections;
    }

    function add($fields){
        $el = new \CIBlockElement;

        $arLoad = $fields;
        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $PRODUCT_ID = $el->Add($arLoad);

        return $PRODUCT_ID;
    }

    function update($id, $fields){
        $el = new \CIBlockElement;

        $arLoad = $fields;
        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $res = $el->update($id, $arLoad);

        return $res;
    }

    function delete($id){
        $el = new \CIBlockElement;

        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $res = $el->Delete($id);

        return $res;
    }

}