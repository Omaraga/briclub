<section class="cogort">
    <div class="container">
        <h4 class="h4">Для кого разработан <?=$data['type_text']?>:</h4>
        <?if(!empty($data['stat_text'])){?>
            <?
            $i = 0;
            foreach ($data['stat_text'] as $datum) {
                ?>
                <p class="cogort-name"><?=$datum['title']?></p>
                <p><?=$data['stat_num'][$i]['title']?></p>
                <?
                $i++;}
            ?>
        <?}?>
    </div>
</section>