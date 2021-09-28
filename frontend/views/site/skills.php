<div class="skills" id="s-<?=$id?>">
    <div class="container">
        <div class="hgroupwrap">
            <h1 class="h1"><?=$data['title']?></h1>
            <div class="carret pull-left"></div>
            <div class="ckearfix"></div>
        </div>

        <div class="row">
            <?
            $i = 0;
            foreach ($data['tab_text'] as $datum) {
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="item-skill">
                        <img src="<?=$data['tab_icon'][$i]['link']?>" alt="" class="icon-skills">
                        <div class="text-block-skills">
                            <h3 class="h3"><?=$data['tab_title'][$i]['title']?></h3>
                        </div>
                        <p class="p"><?=$datum['title']?></p>
                    </div>
                </div>
                <?
                $i++;}
            ?>
        </div>
    </div>
</div>