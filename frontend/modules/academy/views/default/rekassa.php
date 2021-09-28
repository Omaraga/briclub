<?php
?>

<?$form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
    <div class="container mt-5" style="margin-bottom: 200px!important; box-shadow: 0px 4px 4px rgb(192 190 190 / 25%); padding-top: 10px; border-radius: 8px; flex-direction: column; width: 616px; margin: 0 auto;">
        <h3>Загрузка чека rekassa</h3>
        <p class="mt-4 mb-4 ">Загрузите чек за текущий день для новых пользователей образовательной платформы LSE</p>
        <?=$form->field($model, 'file')->fileInput()->label(false);?>
        <button class="btn btn-default mb-4" type="submit">Сохранить</button>
        <p>Текущий чек:</p>
        <embed src="\certs/rekassa.pdf#zoom=95" width="500px" height="600px">
    </div>
<?\yii\widgets\ActiveForm::end();?>


