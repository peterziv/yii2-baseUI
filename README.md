# yii2-basewidgets
Some simple widgets for Yii2

## 安装 (Installation):
```
$ composer require "peterziv/yii2-basewidgets:dev-master"
```

## 示例 (Demo):

### AccountController:
```php
    public function actionList() {
        $all = [ 11 => [1 => 'A1', 2 => 'A2', 3 => 'A3'], 22 => [1 => 'b1', 2 => 'b2', 3 => 'b3']];
        $type = $_POST['account_type'];
        if (isset($type) && in_array($type, array_keys($all))) {
            $list = $all[$type];
            foreach ($list as $value => $name) {
                $htmlOptions = array('value' => $value);
                echo Html::tag('option', Html::encode($name), $htmlOptions, true);
            }
        }
    }
```

### view:

```php
use peterziv\basewidgets\ZSelect2Widget;
use yii\widgets\ActiveForm;
use app\models\ModelA;
...

$types =[11=>'a1',22=>'a2'];
$list2 = [1=>'int_b1',2=>'int_b2'];
$model = new ModelA;// new a model if need

// how to use ZSelect2Widget
// dropdownlist #2 will be impacted when changing dropdownlist #1
// the options of dropdownlist #2 is from postUrl account/list
echo ZSelect2Widget::widget([
  'label' => 'Part ZSelect2Widget',
  'formid' => $form->getId(),
  'dropDownList1Name' => 'account_type',
  'postUrl' => 'account/list',
  'initList1' => $types,
  'model' => $model, // it is optional, if id_A is not the property of one model
  'dropDownList2ID' => 'id_A',
  'initList2' => $list2,
]);

...
```

```php
use peterziv\basewidgets\ZSelect2InputWidget;
use yii\widgets\ActiveForm;
use app\models\ModelA;
...

$types =[11=>'a1',22=>'a2'];
$list2 = [1=>'int_b1',2=>'int_b2'];
$model = new ModelA;// new a model

// how to use ZSelect2InputWidget
// input will be the same as the value of seleted selection in dropdownlist #2
echo ZSelect2InputWidget::widget([
  'label' => 'Part ZSelect2InputWidget',
  'formid' => $form->getId(),
  'dropDownList1Name' => 'account_type',
  'postUrl' => 'account/list',
  'initList1' => $types,
  'model' => $model, // it is optional, if id_B is not the property of one model
  'dropDownList2ID' => 'id_B',
  'initList2' => $list2,
  'inputModel' => $model,
  'inputID' => 'property_of_ModelA'
]);

...
```
