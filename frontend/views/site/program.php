<section class="program" id="s-<?=$id?>">
    <div class="container">
        <h4 class="h4"><?=$data['modul']?></h4>
        <div id="accordion" class="program-accordion">
            <?
            $i = 0;
            foreach ($data['point_title'] as $datum) {
                $punkts = explode(";",$data['point_text'][$i]['title']);
                $i++;
                ?>
                <div class="card">
                    <div class="card-header" id="heading<?=$i?>">
                        <h5 class="mb-0">
                            <button class="program-title" data-toggle="collapse" data-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
                      <span class="program-title-group">
                          <span class="program-title-group-number"><?=$i?>.</span><span class="program-title-group-nameCourse"><?=$datum['title']?></span>
                      </span>
                                <!--<span class="status free">Free</span>-->
                                <!-- Статусы
                                <span class="status price">1 909 тенге</span>
                                <span class="status practise">Практика</span>
                                <span class="status test">Тест</span>
                                <span class="status homework">Домашняя работа</span>
                                -->
                            </button>
                        </h5>
                    </div>

                    <div id="collapse<?=$i?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <!--<h6 class="h6">Программа курса</h6>-->
                            <?if(count($punkts)>1){?>
                                <ul>
                                <?
                                foreach ($punkts as $punkt) {
                                    if($punkt){
                                        ?>
                                        <li><?=$punkt?></li>
                                    <?}}?>
                                </ul>
                            <?}else{?>
                                <p><?=$punkts[0]?></p>
                            <?}?>
                        </div>
                    </div>
                </div>
                <?}?>
        </div>
    </div>
</section>