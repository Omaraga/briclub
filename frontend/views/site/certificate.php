<div class="certificate" id="s-<?=$id?>">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="balancer">
                    <div class="hgroupwrap">
                        <h1 class="h1"><?=$data['title']?></h1>
                        <div class="carret"></div>
                    </div>
                    <p class="p"><?=$data['text']?></p>
                </div>
            </div>
            <div class="wrapCarousel">
                <div class="testiomoial-block">
                    <div class="owl-carousel tet owl-theme">
                        <?
                        $i = 0;
                        foreach ($data['tab_cert'] as $datum) {
                            ?>
                            <div class="item"><a href="#" class="btn-image"><img src="<?=$datum['link']?>" alt="Certificate1" class="img-cert"></a></div>
                            <?
                            $i++;}
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>