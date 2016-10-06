<?php

namespace peterziv\basewidgets;

use yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * 
 * This is the 2 level selection control.
 *
 * @property string label Title of the widget.
 * @property array $initList1 It is data of dropdown list #1
 * @property array $initList2 It is initial data of dropdown list #2
 * @property string $postUrl the post url to get new data of dropdown list #2. The default is account/list
 * @property string $dropDownList1Name the element name of dropdown list #1, it also the key post item to refresh data of dropdown list #2. The default is account_type
 * @property \yii\db\ActiveRecord $model (Optional)It is optional. The model object that can be used to render views.
 * @property string $dropDownList2ID if $model exists, it will define the id and name of dropdown list #2 by $model, orwise it is self for the id and name of dropdown list #2. The default is account_id
 *
 * @author Peter (peter.ziv@hotmail.com)
 * @copyright Copyright (c) 2016
 * @example 
            echo ZSelect2Widget::widget([
                    'label' => 'From',
                    'formid' => $form->getId(),
                    'dropDownList1Name' => 'account_type',
                    'postUrl' => 'account/list',
                    'initList1' => $accountTypes,
                    'model' => $model,
                    'dropDownList2ID' => 'account_id',
                    'initList2' => $initAccounts,
                ]);
 */
class ZSelect2Widget extends Widget {

    public $label;
    public $formid; //string
    public $initList1;
    public $initList2;
    public $postUrl = 'account/list';
    public $dropDownList1Name = 'account_type';
    public $dropDownList2ID = 'account_id';// the dropdownlist #2 name is the same., if $model != null, it will be attribute of dropdown list #2
    public $model = null;

    public function init() {
        parent::init();
    }

    public function run() {
        $script = Html::beginTag('div');
        $script.= Html::label($this->label);
        $script.= Html::endTag('div');
        $script.= Html::beginTag('div',['class' => "body-content"]);
        $script.= Html::beginTag('div', ['class' => "row"]);
        $script.= Html::beginTag('div', ['class' => "col-lg-5"]);
        $script.= Html::dropDownList($this->dropDownList1Name, null, $this->initList1, array(
            'onchange' => '$.ajax({cache: false,type: "POST",'
                . 'url:"' . yii::$app->urlManager->createUrl($this->postUrl) . '",'
                . 'data:$("#' . $this->formid . '").serialize(),'
                . 'async: false,error: function(request) {alert("Connection error");},'
                . 'success: function(data) {$("#'.$this->getIDofDropDownList2().'").html(data);}});',
            'class' => 'form-control',
            ));
        $script.= Html::endTag('div');
        $script.= Html::beginTag('div', ['class' => "col-lg-7"]);
        $script.= $this->getElementofDropDownList2();
        $script.= Html::endTag('div');
        $script.= Html::endTag('div');
        $script.= Html::endTag('div');

        return $script;
    }
    
    protected function getIDofDropDownList2(){
        $id2 = $this->dropDownList2ID;
        if(!is_null($this->model)){
            $id2 = Html::getInputId($this->model,  $this->dropDownList2ID);
        }
        return $id2;
    }
    
    protected function getElementofDropDownList2(){
        if (is_null($this->model)) {
            return Html::dropDownList($this->dropDownList2ID, null, $this->initList2, ['class' => 'form-control', 'id' => $this->dropDownList2ID]);
        } 
        return Html::activeDropDownList($this->model, $this->dropDownList2ID, $this->initList2, ['class' => 'form-control']);
    }
}