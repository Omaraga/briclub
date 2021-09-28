<div class="testimonial" id="s-<?=$id?>">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <div class="hgroupwrap">
                    <h1 class="h1"><?=$data['title']?></h1>
                    <div class="carret"></div>
                </div>

                <p class="p hidden-sm"><?=$data['text']?></p>
                <div class="btn-wrap hidden-sm">
                    <div data-toggle="modal" data-target="#commentModal" class="btn btn-default"><?=$data['button']?></div>
                </div>
            </div>
            <div class="wrapCarousel">
                <div class="testiomoial-block">
                    <div class="owl-carousel ter owl-theme">
                        <?
                        $i = 0;
                        foreach ($data['tab_title'] as $datum) {
                            ?>
                            <div class="item">
                                <div class="TesimonialHeader">
                                    <div class="photoTestimonialBlock pull-left"><img src="<?=$data['tab_icon'][$i]['link']?>" alt=""></div>
                                    <div class="TestimonialNameBlock pull-left">
                                        <p class="p"><b><?=$data['tab_author'][$i]['title']?></b></p>
                                        <p class="p"><?=$data['tab_prof'][$i]['title']?></p>
                                    </div>
                                </div>
                                <h3 class="h3"><?=$datum['title']?></h3>
                                <?=$data['tab_text'][$i]['title']?>
                            </div>
                            <?
                            $i++;}
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>