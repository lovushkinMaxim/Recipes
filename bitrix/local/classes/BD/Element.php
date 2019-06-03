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
        "id",
        "active",
        "iblock_id",
        "name",
        "preview_text",
        "preview_picture",
        "detail_text",
    ];
    protected $defaultFilter = [
        "active" => 'Y',
    ];
    protected $defaultExtraFields = [
        'id'=>"ID",
        'active'=>"ACTIVE",
        'iblock_id'=>"IBLOCK_ID",
        'iblock_type'=>"IBLOCK_TYPE",
        'name'=>"NAME",
        'preview_text'=>"PREVIEW_TEXT",
        'preview_picture'=>"PREVIEW_PICTURE",
        'detail_text'=>"DETAIL_TEXT",
        'detail_text_type'=>"DETAIL_TEXT_TYPE",
        'preview_text_type'=>"PREVIEW_TEXT_TYPE",
    ];
    protected $extraFields = [];

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

    function getList(array $params = []){

        $arSelect = array_merge($this->defaultSelect,$params['select']?$params['select']:[]);
        $arSelect = array_unique($arSelect);

        $arFilter = array_merge($this->defaultFilter,$params['filter']?$params['filter']:[]);

        if ($this->iBlock){
            $arFilter["iblock_type"]=$this->iBlock['IBLOCK_TYPE_ID'];
            $arFilter["iblock_id"]=$this->iBlock['ID'];
        }

        $arNav = false;
        if ($params['limit']){
            $arNav['nTopCount'] = $params['limit'];
        }

        $arSelect = $this->unsetExtraFields($arSelect, true);
        $arFilter = $this->unsetExtraFields($arFilter);
        $arOrder = $this->unsetExtraFields($params['sort']);

        $result = [];
        $bdRes = \CIBlockElement::GetList($arOrder, $arFilter,false,$arNav,$arSelect);
        while($ob = $bdRes->GetNextElement()) {
            $props = $ob->GetProperties();
            $arFields = $ob->GetFields();
            foreach ($props as $prop){
                $arFields[$prop["CODE"]] = $prop['VALUE'];
                $arFields[$prop["CODE"].'_id'] = $prop['PROPERTY_VALUE_ID'];
            }
            $result[$arFields['ID']] = $this->setExtraFields($arFields);
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

    public function getSections($params){
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

    public function add($fields){
        $el = new \CIBlockElement;

        $arLoad = $fields;
        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $PRODUCT_ID = $el->Add($arLoad);

        return $PRODUCT_ID;
    }

    public function update($id, $fields){
        $el = new \CIBlockElement;

        $arLoad = $fields;
        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $res = $el->update($id, $arLoad);

        return $res;
    }

    public function delete($id){
        $el = new \CIBlockElement;

        $arLoad["IBLOCK_ID"] = $this->iBlock['ID'];

        $res = $el->Delete($id);

        return $res;
    }

    private function setExtraFields($fields){
        $newFields = array_merge($this->defaultExtraFields,$this->extraFields);
        $newFields = array_flip($newFields);
        foreach ($fields as $key => $field){
            if (isset($newFields[$key])){
                $fields[$newFields[$key]] = $field;
                unset($fields[$key]);
            }
        }
        return $fields;
    }
    private function unsetExtraFields($fields,$isSelect=false){
        $newFields = array_merge($this->defaultExtraFields,$this->extraFields);
        foreach ($fields as $key => $field){
            if ($isSelect){
                if (isset($newFields[$field])){
                    $fields[] = $newFields[$field];
                    unset($fields[$key]);
                }
            }else{
                if (isset($newFields[$key])){
                    $fields[$newFields[$key]] = $field;
                    unset($fields[$key]);
                }
            }
        }
        return $fields;
    }

}