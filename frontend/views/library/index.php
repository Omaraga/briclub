<?php

/* @var $this yii\web\View */

$this->title = $book->title ;
?>

    <main class="cours">
        <section class="cours-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="h3 pt-5 pb-5"><?=$this->title?></h3>
                    </div>
                    <img src="<?= $book['link2']?>">
                </div>
            </div>
        </section>
        <div class="container">
            <h4 class="mt-2">Аудиодорожки книги:</h4>
            <div class="row">
                <? foreach ($audios as $audio){?>
                    <div class="col-12 mt-2 mb-2">
						<div class="row">
							<div class="col-4">
								<audio src="<?=$audio->link?>" controls></audio>
							</div>
							<div class="col-3">
								<a target="_blank" class="btn btn-success" download href="<?=$audio['link']?>">Скачать</a>
							</div>
						</div>
                       
                    </div>
                <?} ?>
            </div>
        </div>
    </main>
<?
echo \frontend\components\LoginWidget::widget();
?>