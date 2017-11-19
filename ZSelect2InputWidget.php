<?php

namespace peterziv\basicwidgets;

use yii;
use yii\helpers\Html;
use peterziv\basicwidgets\ZSelect2Widget;

/**
 *
 * This is the multiple linkage control: two selections and one input element.
 * It is extends on ZSelect2Widget
 *
 * @property string label                     Title of the widget.
 * @property string $postUrl                  the post url to get new data of dropdown list #2. The default is account/list
 * @property \yii\db\ActiveRecord $model      (Optional)It is optional. The model object that can be used to render views.
 * @property array $initList1                 It is data of dropdown list #1
 * @property string $dropDownList1Id          the element name and id of dropdown list #1. If $model exists, it will define the id and name of dropdown list #2 by $model. it also the key post item to refresh data of dropdown list #2. The default is account_type
 * @property \yii\db\ActiveRecord $model2     (Optional)It is optional. The model object that can be used to render views.
 * @property array $initList2                 It is initial data of dropdown list #2
 * @property string $dropDownList2Id          if $model2 exists, it will define the id and name of dropdown list #2 by $model2, orwise it is self for the id and name of dropdown list #2. The default is account_id
 * @property \yii\db\ActiveRecord $inputModel It is optional. The model object that can be used to render views for input element.
 * @property string $inputId                  need $inputModel existing, it will define the id of input element by $inputModel.
 *
 *
 * @author Peter (peter.ziv@hotmail.com)
 * @copyright Copyright (c) 2016
 * @example    echo ZSelect2InputWidget::widget([
                    'label' => 'From',
                    'formid' => $form->getId(),
                    'postUrl' => 'account/list',
                    'model' => $model,
                    'dropDownList1Id' => 'account_type',
                    'initList1' => $accountTypes,
                    'model2' => $model,
                    'dropDownList2Id' => 'account_id',
                    'initList2' => $initAccounts,
                    'inputModel' => $model,
                    'inputId' => 'input_id'
                ]);
 */
class ZSelect2InputWidget extends ZSelect2Widget {

    public $inputModel = null;
    public $inputId = null;

    public function init() {
        parent::init();
    }

    public function run() {
        $script = parent::run();
        $script .= Html::beginTag('div',['class'=>'form-group']);
        $script .= Html::tag('div', '', ['class' => "help-block"]);
        $script .= Html::beginTag('div');
        $script .= Html::activeTextInput($this->inputModel, $this->inputId, ['class' => 'form-control']);
        $script .= Html::endTag('div');
        $script .= Html::tag('div', '', ['class' => "help-block"]);
        $script .= Html::endTag('div');

        $script .= $this->getJS();

        return $script;
    }

    protected function changeOnDropDownList1() {
        return '$.ajax({cache: false,type: "POST",'
                . 'url:"' . yii::$app->urlManager->createUrl($this->postUrl) . '",'
                . 'data:$("#' . $this->formid . '").serialize(),'
                . 'async: false,error: function(request) {alert("Connection error");},'
                . 'success: function(data) {loadByAccountType(data);}});';
    }

    protected function getElementOfDropDownList2()
    {
        if (is_null($this->model2)) {
            return Html::dropDownList($this->dropDownList2Id, null, $this->initList2, ['class' => 'form-control', 'id' => $this->dropDownList2Id, 'onchange' => 'setFromName();']);
        }
        return Html::activeDropDownList($this->model2, $this->dropDownList2Id, $this->initList2, ['class' => 'form-control', 'onchange' => 'setFromName();']);
    }

    protected function getJS()
    {
        $input3ID = Html::getInputId($this->inputModel, $this->inputId);
        $js = Html::beginTag('script', ['language' => "javascript"]);
        $js.= 'function setCreditor(text){'
                . 'var input = document.getElementById("'. $input3ID .'");'
                . 'input.value = text;'
                . '}';

        $js.= 'function setFromName(){'
                . 'var sel2 = document.getElementById("' . $this->getIdOfDropDownList2() . '");'
                . 'setCreditor(sel2.options[sel2.selectedIndex].text);'
                . '}';

        $js.= 'function loadByAccountType(data){'
                . '$("#' . $this->getIdOfDropDownList2() . '").html(data);'
                . 'setFromName();'
                . '}';

        $js.= 'window.onload=function(){setFromName();}';

        $js.= Html::endTag('script');
        return $js;
    }
}