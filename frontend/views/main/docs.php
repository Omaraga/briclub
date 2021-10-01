<?php
$docs = \common\models\Documents::find()->where(['type'=>1,'status'=>1])->orderBy('order asc')->all();

?>
<style>
    .column .between{
        width: 600px;
        height: 60px;
    }
    @media screen and (max-width: 992px){
        .block-column{
            margin-top: 120px;
            padding: 0 16px;
        }
    }
    @media screen and (max-width: 768px){
        .column .between {
            width: 543px;
        }
    }
    @media screen and (max-width: 575px){
        .column .between {
            width: 100%;
        }
    }


</style>
<main>
    <div class="block-column">
        <h4 class="mb-4">Документы</h4>
        <div class="column">
            <?foreach ($docs as $doc):?>
                <div class="between fon-main mb-3 px-4 ">
                    <div>
                        <img src="/img/main/carbon_document.svg" alt="">
                        <span><?=$doc['title'];?></span>
                    </div>
                    <a href="<?=isset($doc['link2']) ? $doc['link2'] : $doc['link']?>"><span style="color: #00CDA8;">Скачать</span></a>
                </div>
            <?endforeach;?>
        </div>
    </div>
</main>
