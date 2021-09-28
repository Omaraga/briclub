<?php

/* @var $this yii\web\View */



$this->title = $course['title'];
$screens = \common\models\CourseScreens::find()->where(['course_id'=>$id,'is_active'=>1])->orderBy('position asc')->all();
foreach ($screens as $screen) {
    if($screen['screen_id'] == 1){
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>5])->one();
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>6])->one()['title'];
        $alias = 'sale';
    }elseif($screen['screen_id'] == 2){
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>7])->one()['title'];
        $data['des'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>8])->one()['title'];
        $data['img'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>9])->one()['link'];
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>10])->one()['title'];
        $data['alias'] = $course['alias'];
        $data['price'] = $course['price'];
        $data['old_price'] = $course['old_price'];
        $data['duration'] = $course['duration'];
        $data['course_id'] = $course['id'];
        $data['type'] = $course['type'];
        if(count(\common\models\Lessons::find()->where(['course_id'=>$course['id']])->all())>1){
            $data['type_text'] = "курс";
        }else{
            $data['type_text'] = "урок";
        }

        $alias = 'main';

    }elseif($screen['screen_id'] == 3){
        $alias = 'footer';
        $data['soc'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>11])->all();
        $data['email'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>12])->one()['title'];
        $data['number'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>13])->one()['title'];
        $data['adres'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>14])->one()['title'];
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>15])->one()['title'];
        $data['course_id'] = $course['id'];
    }elseif($screen['screen_id'] == 4){
        $alias = 'about';
        $data['stat_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>18])->all();
        $data['stat_num'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>19])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>16])->one()['title'];
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>17])->one()['title'];
    }elseif($screen['screen_id'] == 6){
        $alias = 'program';
        $data['point_title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>43])->all();
        $data['point_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>44])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>41])->one()['title'];
        $data['modul'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>42])->one()['title'];
        $data['cost'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>45])->one()['title'];
        $data['cost_sale'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>46])->one()['title'];
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>47])->one()['title'];
    }elseif($screen['screen_id'] == 7){
        $alias = 'lekt';
        $data['lek_name'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>36])->all();
        $data['lek_prof'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>37])->all();
        $data['lek_about_title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>38])->all();
        $data['lek_about_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>39])->all();
        $data['lek_soc'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>40])->all();

        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>35])->one()['title'];
    }elseif($screen['screen_id'] == 8){
        $alias = 'whois';
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>22])->one()['title'];
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>21])->one()['title'];
    }elseif($screen['screen_id'] == 9){
        $alias = 'question';

        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>48])->one()['title'];
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>49])->one()['title'];
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>50])->one()['title'];
    }elseif($screen['screen_id'] == 10){
        $alias = 'other';
        $data['tab_title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>59])->all();
        $data['tab_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>60])->all();
        $data['tab_img'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>61])->all();
        $data['tab_link'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>62])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>58])->one()['title'];
    }elseif($screen['screen_id'] == 11){
        $alias = 'pack';
        $data['tab_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>71])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>70])->one()['title'];
        $data['price'] = $course['price'];
        $data['old_price'] = $course['old_price'];
        $data['duration'] = $course['duration'];
        $data['course_title'] = $course['title'];
        $data['course_id'] = $course['id'];
        if(count(\common\models\Lessons::find()->where(['course_id'=>$course['id']])->all())>1){
            $data['type_text'] = "курс";
        }else{
            $data['type_text'] = "урок";
        }
    }elseif($screen['screen_id'] == 12){
        $alias = 'skills';
        $data['tab_title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>24])->all();
        $data['tab_icon'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>25])->all();
        $data['tab_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>26])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>23])->one()['title'];
    }elseif($screen['screen_id'] == 13){
        $alias = 'faq';
        $data['tab_question'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>53])->all();
        $data['tab_answer'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>76])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>52])->one()['title'];
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>54])->one()['title'];
    }elseif($screen['screen_id'] == 14){
        $alias = 'testimonial';
        $data['tab_author'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>30])->all();
        $data['tab_icon'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>31])->all();
        $data['tab_prof'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>32])->all();
        $data['tab_title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>33])->all();
        $data['tab_text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>34])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>27])->one()['title'];
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>28])->one()['title'];
        $data['button'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>29])->one()['title'];
    }elseif($screen['screen_id'] == 15){
        $alias = 'certificate';
        $data['tab_cert'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>57])->all();
        $data['title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>55])->one()['title'];
        $data['text'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>56])->one()['title'];
    }elseif($screen['screen_id'] == 17){
        $alias = 'target';
        $data['title1'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>64])->one()['title'];
        $data['img1'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>64])->one()['link'];
        $data['text1'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>65])->one()['title'];
        $data['link1'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>68])->one()['link'];
        $data['link1-title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>68])->one()['title'];

        $data['title2'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>66])->one()['title'];
        $data['img2'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>66])->one()['link'];
        $data['text2'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>67])->one()['title'];
        $data['link2'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>69])->one()['link'];
        $data['link2-title'] = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>69])->one()['title'];
    }
    echo $this->render('/site/'.$alias,['data'=>$data,'id'=>$screen['id']]);
}
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
echo \frontend\components\LetauthWidget::widget(['course_id'=>$course['id']]);
echo \frontend\components\BedWidget::widget(['course_id'=>$course['id']]);
?>

