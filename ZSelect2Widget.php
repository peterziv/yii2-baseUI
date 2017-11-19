<?php

namespace peterziv\basicwidgets;

use yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 *
 * This is the 2 level selection control.
 *
 * @property string label                 Title of the widget.
 * @property string $postUrl              the post url to get new data of dropdown list #2. The default is account/list
 * @property \yii\db\ActiveRecord $model  (Optional)It is optional. The model object that can be used to render views.
 * @property array $initList1             It is data of dropdown list #1
 * @property string $dropDownList1Id      the element name and id of dropdown list #1. If $model exists, it will define the id and name of dropdown list #2 by $model. it also the key post item to refresh data of dropdown list #2. The default is account_type
 * @property \yii\db\ActiveRecord $model2 (Optional)It is optional. The model object that can be used to render views.
 * @property array $initList2             It is initial data of dropdown list #2
 * @property string $dropDownList2Id      if $model2 exists, it will define the id and name of dropdown list #2 by $model2, orwise it is self for the id and name of dropdown list #2. The default is account_id
 *
 * @author Peter (peter.ziv@hotmail.com)
 * @copyright Copyright (c) 2016
 * @example  echo ZSelect2Widget::widget([
 *                     'label' => 'From',
 *                     'formid' => $form->getId(),
 *                     'model' => $model,
 *                     'dropDownList1Id' => 'account_type',
 *                     'postUrl' => 'account/list',
 *                     'initList1' => $accountTypes,
 *                     'model2' => $model,
 *                     'dropDownList2Id' => 'account_id',
 *                     'initList2' => $initAccounts,
 *                 ]);
 */
class ZSelect2Widget extends Widget {

    public $label;
    public $formid; //string
    public $postUrl = 'account/list';

    //param for dropdown list #1
    public $model = null;
    public $dropDownList1Id = 'account_type'; // the dropdownlist #1 name is the same., if model != null, it will be attribute of dropdown list #1
    public $initList1;

    //param for dropdown list #2
    public $model2 = null;
    public $dropDownList2Id = 'account_id'; // the dropdownlist #2 name is the same., if model2 != null, it will be attribute of dropdown list #2
    public $initList2;

    public function init() {
        parent::init();
    }

    public function run() {
        $script = Html::beginTag('div', ['class' => 'form-group']);
        $script .= Html::label($this->label, '', ['class' => "control-label"]);
        $script .= Html::beginTag('div', ['class' => "body-content"]);
        $script .= Html::beginTag('div', ['class' => "row"]);
        $script .= Html::beginTag('div', ['class' => "col-lg-5"]);
        $script .= $this->getElementofDropDownList1();
        $script .= Html::endTag('div');
        $script .= Html::beginTag('div', ['class' => "col-lg-7"]);
        $script .= $this->getElementofDropDownList2();
        $script .= Html::endTag('div');
        $script .= Html::endTag('div');
        $script .= Html::endTag('div');
        $script .= Html::endTag('div');

        return $script;
    }

    protected function getElementofDropDownList1()
    {
        if (is_null($this->model)) {
            return Html::dropDownList($this->dropDownList1Id, null, $this->initList1, [
                    'onchange' => $this->changeOnDropDownList1(),
                    'class' => 'form-control',
                    'id' => $this->dropDownList2Id
            ]);
        }
        return Html::activeDropDownList($this->model, $this->dropDownList1Id, $this->initList1, [
                'onchange' => $this->changeOnDropDownList1(),
                'class' => 'form-control',
        ]);
    }

    protected function changeOnDropDownList1() {
        return '$.ajax({cache: false,type: "POST",'
            . 'url:"' . yii::$app->urlManager->createUrl($this->postUrl) . '",'
            . 'data:$("#' . $this->formid . '").serialize(),'
            . 'async: false,error: function(request) {alert("Connection error");},'
            . 'success: function(data) {$("#' . $this->getIDofDropDownList2() . '").html(data);}});';
    }


    protected function getIDofDropDownList2(){
        $id2 = $this->dropDownList2Id;
        if (!is_null($this->model2)) {
            $id2 = Html::getInputId($this->model2, $this->dropDownList2Id);
        }
        return $id2;
    }

    protected function getElementofDropDownList2(){
        if (is_null($this->model2)) {
            return Html::dropDownList($this->dropDownList2Id, null, $this->initList2, ['class' => 'form-control', 'id' => $this->dropDownList2Id]);
        }
        return Html::activeDropDownList($this->model2, $this->dropDownList2Id, $this->initList2, ['class' => 'form-control']);
    }
}