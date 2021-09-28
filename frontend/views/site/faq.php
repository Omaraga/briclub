<div class="faq" id="s-<?=$id?>">
    <div class="container">
        <div class="hgroupwrap">
            <h1 class="h1 text-center"><?=$data['title']?></h1>
            <div class="carret center"></div>
        </div>
        <div class="row">
            <?
            $i = 0;
            foreach ($data['tab_question'] as $datum) {
                ?>
                <div class="col-md-10 col-lg-9 col-sm-10 col-md-offset-2 col-sm-offset-2 col-xs-11 col-xs-offset-1 questionFAQ FAQblock">
                    <div class="item-faq-quest">
                        <p class="p"><?=$datum['title']?></p>
                    </div>
                </div>
                <div class="col-md-10 col-lg-9 col-sm-10 col-lg-offset-1 col-sm-offset-0 col-xs-11 col-xs-offset-0 answerFAQ FAQblock">
                    <div class="item-faq-quest">
                        <p class="p"> <?=$data['tab_answer'][$i]['title']?></p>
                    </div>
                </div>
                <?
                $i++;}
            ?>
        </div>

        <div class="btn-wrap text-center">
            <a href="#" data-toggle="modal" data-target="#questionModal" class="btn btn-default">Задать свой вопрос</a>
        </div>
    </div>
</div>