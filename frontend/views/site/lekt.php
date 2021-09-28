<section class="techers" id="s-<?=$id?>">
    <div class="container">
        <h4 class="h4"><?=$data['title']?></h4>
        <?
        $i = 0;
        foreach ($data['lek_name'] as $datum) {
            ?>
            <div class="techers-wrap">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="techers-photo"><img src="<?=$data['lek_soc'][$i]['link']?>" alt="" class="techers-photo-img"></div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="techers-info">
                            <h5 class="name"><?=$datum['title']?></h5>
                            <p class="prof"><?=$data['lek_about_title'][$i]['title']?></p>
                            <p class="about-text"><?=$data['lek_about_text'][$i]['title']?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?
            $i++;}
        ?>

    </div>
</section>