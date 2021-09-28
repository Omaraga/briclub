<div class="other" id="s-<?=$id?>">
    <div class="container">
        <div class="hgroupwrap">
            <h1 class="h1"><?=$data['title']?></h1>
            <div class="carret"></div>
        </div>

        <div class="row">
            <?
            $i = 0;
            foreach ($data['tab_title'] as $datum) {
                ?>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="<?=$data['tab_img'][$i]['link']?>" alt="...">
                        <div class="caption">
                            <h3 class="h3"><?=$datum['title']?></h3>
                            <p class="p"><?=$data['tab_text'][$i]['title']?></p>
                            <a href="<?=$data['tab_link'][$i]['title']?>" class="btn btn-link" role="button">Страница курса</a>
                        </div>
                    </div>
                </div>
                <?
                $i++;}
            ?>

        </div>

    </div>
</div>