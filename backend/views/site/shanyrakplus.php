<?php

/* @var $this yii\web\View */

$this->title = 'Матрица';
$main_mats = \common\models\MatrixRef::find()->where(['platform_id'=>1,'parent_id'=>null])->orderBy('id asc')->all();
?>
<style>
    .tree span:hover {
        font-weight: bold;
    }

    .tree span {
        cursor: pointer;
    }
    .mat-info{
        border: 1px solid #efc95e;
        width: 200px;
        padding: 7px;
        margin: 9px;
        display: block;
        background-color: #fff;
        border-radius: 10px;
    }
    .ul-item{
        padding-left: 100px;
    }
</style>
<div class="site-index">
    <ul class="tree" id="tree">
        <?
        foreach ($main_mats as $main_mat) {
            \backend\controllers\SiteController::getDom($main_mat['id']);
        }
        ?>
    </ul>
</div>
<script>
    // поместить все текстовые узлы в элемент <span>
    // он занимает только то место, которое необходимо для текста
    for (let li of tree.querySelectorAll('li')) {
        let span = document.createElement('span');
        li.prepend(span);
        span.append(span.nextSibling); // поместить текстовый узел внутрь элемента <span>
    }

    //  ловим клики на всём дереве
    tree.onclick = function(event) {

        if (event.target.tagName != 'SPAN') {
            return;
        }

        let childrenContainer = event.target.parentNode.querySelector('ul');
        if (!childrenContainer) return; // нет детей

        childrenContainer.hidden = !childrenContainer.hidden;
    }
</script>