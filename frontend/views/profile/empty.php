<?php
function getActivityBar($activityValue){
    $html = '';
    for($i=0; $i<100;$i+=16.7){
        if($i < $activityValue){
            $html.='<div class="col rating active"></div>';
        }else{
            $html.='<div class="col rating"></div>';
        }
    }
    return $html;
}
function getDropDown($obj){

    $clones = \common\models\MatrixRef::find()->select(['id','time'])->where(['user_id'=>$obj['user_id'],'platform_id'=>$obj['platform_id'],'reinvest'=>1])->orderBy('platform_id desc')->all();
    $origins = \common\models\MatrixRef::find()->select(['id','time'])->where(['user_id'=>$obj['user_id'],'platform_id'=>$obj['platform_id'],'reinvest'=>0])->orderBy('platform_id desc')->all();
    $optionClone = '';
    foreach ($clones as $item_m) {
        $optionClone .= '<option value="'.$item_m['id'].'">["'.sprintf("%'06d\n", $item_m['id']).'] '.date('d.m.Y H:i',$item_m['time']).'</option>';
    }
    $optionOrigin = '';
    foreach ($origins as $item_o) {
        $optionOrigin .= '<option value="'.$item_o['id'].'">['.sprintf("%'06d\n", $item_o['id']).'] '.date('d.m.Y H:i',$item_o['time']).'</option>';
    }
    $html = '<p class="mb-0 mr-4 pl-0 col-12">Клоны ('.count($clones).')</p>'.
        '<div class="form-group input-group col">'.
            '<select name="" id="clones" class="clones form-control">'.
                '<option value="0">Выберите клона</option>'.
                $optionClone.
            '</select>'.
        '</div>'.
        '<p class="mb-0 mr-4 pl-0 col-12">Выкупленные места ('.count($origins).')</p>'.
        '<div class="form-group">'.
            '<select name="" id="clones" class="clones form-control">'.
                '<option value="0">Выберите место</option>'.
                $optionOrigin.
            '</select>'.
        '</div>';
    return $html;
}
function getModalContent($obj, $dropDown = false)
{
    $maxMatrixLevel = \common\models\MatrixRef::find()->where(['user_id'=>$obj['user_id']])->orderBy('platform_id desc')->one();
    $currLevel = intval($maxMatrixLevel['platform_id']);
    $currLevelChildren = intval($maxMatrixLevel['children']);
    $activityValue = intval(100 * ((($currLevel-1) * 6) +$currLevelChildren)/36);

    $parentMatrix = \common\models\MatrixRef::findOne($obj['parent_id']);
    $parentUser = \common\models\User::findOne($parentMatrix['user_id']);
    $user = \common\models\User::findOne($obj['user_id']);
    $refmat = \common\models\MatParents::find()->select('id')->where(['parent_id' => $obj['id']])->count();
    $refmat_own = \common\models\Referals::find()->select('id')->where(['parent_id'=>$obj['user_id'],'level'=>1,'activ'=>1])->count();
    $premium = \common\models\Premiums::findOne(['user_id' => $obj['user_id']]);
    if ($premium && $premium->img_source != null) {
        $userAvatar = '<div class="user__img" style = "background: url('.$premium->img_source.') no-repeat; background-size: cover;" ></div >';
    }else{
        $userAvatar = '<div class="user__img" ><img src = "/img/matrix_avatar.svg" alt = "" ></div >';
    }
    if ($dropDown){
        $dropDownHtml = getDropDown($obj);
    }else{
        $dropDownHtml = '';
    }
    $modalHeaderHtml =
        '<div class="modal__header">' .
            '<div>' .
                '<h5 class="modal-title w5" id="exampleModalLabel">Спонсор:' .
                    $parentUser['username'] . '[' . $parentMatrix['id'] . ']' .
                '</h5>' .
                '<p>' . date('d.m.Y H:i', $obj['time']) . '</p>' .
            '</div>' .
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>' .
        '</div>';
    $modalBodyHtml =
        '<div class="modal-body text-center">'.
            '<div class="user-matrix-info">'.
                '<div class="mb-4">'.
                    '<div class="block__user-img">'.
                        $userAvatar.
                    '</div>'.
                '</div>'.
                '<h2 class="h2 w5">'.
                    $user['fio'].'<span style="color: #6e6e6e;">'.(($obj['reinvest'])?'Clone':'').'</span>'.
                    '<p class="text__small">'.$user['phone'].'</p>'.
                '</h2>'.
                '<p class="">Матрица '.$obj['platform_id'].'</p><hr>'.
                '<div class="mt-3">'.
                ' <span class="">Рейтинг активности: <span>'.$activityValue.'%</span></span>'.
                '</div>'.
                '<div class="rating__group mt-3">'.
                    getActivityBar($activityValue).
                '</div>'.
                '<div class="shoulder my-1">'.
                    '<div class="left">'.
                        '<p class="text__small">Людей в структуре</p>'.
                        '<h2 class="text__big w5 h2">'.$refmat.'</h2>'.
                    '</div>'.
                    '<div class="left">'.
                        '<p class="text__small">Личники</p>'.
                        '<h2 class="text__big w5 h2">'.$refmat_own.'</h2>'.
                    '</div>'.
                '</div>'.
                $dropDownHtml.
            '</div>'.
        '</div>';
    return $modalHeaderHtml.$modalBodyHtml;

}
