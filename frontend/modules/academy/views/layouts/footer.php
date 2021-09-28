<?php

$script = <<<JS
$('#agreeContract').click(function (e){
    e.preventDefault();
    var param = $('meta[name="csrf-param"]').attr("content");
    var token = $('meta[name="csrf-token"]').attr("content");
    let data = {'isAgree' : 1, '_csrf-frontend' : token};
    $.ajax({
        url: '/academy/pay/agree-contract',
        data: data,
        type: 'POST',
        dataType:'json',
        success: function(response){
            window.location.href = '/profile';
        }
    });
});

JS;

$this->registerJs($script);
?>
<footer>
    <div class="container">
        <div class="footer-top">
            <h3>LSE Platform
            </h3>
            <a href="mailto:support@briincorp.com">support@briincorp.com</a>

            <div class="footer-top_icon-goup">
                <ul>
                    <li><a href="https://www.instagram.com/lseplatformcom/"><img src="/img/academy/home/insta.svg" alt=""></a></li>
                    <li><a href=""><img src="/img/academy/home/tg.svg" alt=""></a></li>
                    <li><a href="https://www.youtube.com/channel/UCLEKKxpYwUq6H1rd7uKW8LQ"><img src="/img/academy/home/yt.svg" alt=""></a></li>
                </ul>
            </div>
        </div>

        <div class="footer-center footer-size">
            <a href="/"><h4>Все профессии</h4></a>
            <!-- <h4>О платформе</h4> -->
        </div>

        <div class="footer-bottom footer-size">
            <a href="/docs/system/Пользовательское_соглашение.pdf"><h6>Политика конфиденциальности</h6></a>
            <a href="/docs/system/оферта.pdf"><h6>Договор оферты</h6></a>
            <h6>Лицензии</h6>
        </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-footer-block">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="exampleModalLabel">Агентский договор</h5>
      </div>
      <div class="modal-body">
      <embed class="document" src="/docs/system/Агентский_договор.pdf" width="720px" height="814px" />
      </div>
      <div class="modal-footer">
        <div class="mx-auto">
            <button type="button" class="btn fon-green border-none" id="agreeContract">Принять</button>
            <button type="button" class="btn fon-transparent" data-dismiss="modal">Отказать</button>
        </div>
      </div>
    </div>
  </div>
</div>


</footer>