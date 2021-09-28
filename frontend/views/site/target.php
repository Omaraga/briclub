<div class="target" id="s-<?=$id?>">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-4 col-xs-12 hidden-xs">
                <img src="<?=$data['img1']?>" class="targetMan manIllustration" alt="">
            </div>
            <div class="col-md-6 col-sm-8 col-xs-12">
                <div class="textWrapTarget">
                    <h2 class="h2"><?=$data['title1']?></h2>
                </div>
                <p class="p"><?=$data['text1']?></p>
                <?if($data['link1'] == 'm1'){?>
                    <a data-toggle="modal" data-target="#bedModal" class="btn btn-link"><?=$data['link1-title']?></a>
                <?}elseif($data['link1'] == 'm2'){?>
                    <a data-toggle="modal" data-target="#consultModal" class="btn btn-link"><?=$data['link1-title']?></a>
                <?}elseif($data['link1'] == 'm3'){?>
                    <a data-toggle="modal" data-target="#questionModal" class="btn btn-link"><?=$data['link1-title']?></a>
                <?}elseif($data['link1'] == 'm4'){?>
                    <a data-toggle="modal" data-target="#commentModal" class="btn btn-link"><?=$data['link1-title']?></a>
                <?}else{?>

                <a href="<?=$data['link1']?>" class="btn btn-link flowing-scroll"><?=$data['link1-title']?></a>
                <?}?>
            </div>
            <div class="col-md-6 col-sm-8 col-xs-12">
                <div class="who">
                    <div class="textWrapTarget2">
                        <h2 class="h2"><?=$data['title2']?></h2>
                    </div>
                    <p class="p"><?=$data['text2']?></p>
                    <?if($data['link2'] == 'm1'){?>
                        <a data-toggle="modal" data-target="#bedModal" class="btn btn-link"><?=$data['link2-title']?></a>
                    <?}elseif($data['link2'] == 'm2'){?>
                        <a data-toggle="modal" data-target="#consultModal" class="btn btn-link"><?=$data['link2-title']?></a>
                    <?}elseif($data['link2'] == 'm3'){?>
                        <a data-toggle="modal" data-target="#questionModal" class="btn btn-link"><?=$data['link2-title']?></a>
                    <?}elseif($data['link2'] == 'm4'){?>
                        <a data-toggle="modal" data-target="#commentModal" class="btn btn-link"><?=$data['link2-title']?></a>
                    <?}else{?>

                        <a href="<?=$data['link2']?>" class="btn btn-link flowing-scroll"><?=$data['link2-title']?></a>
                    <?}?>
                </div>
            </div>
            <div class="col-md-6 col-sm-4 col-xs-12 hidden-xs">
                <img src="<?=$data['img2']?>" class="whoMan manIllustration" alt="">
            </div>
        </div>
    </div>
</div>