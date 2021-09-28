<div class="modal fade" id="letauthModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?=$text?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Войдите или зарегистрируйтесь чтобы <?=$text?>
                </p>
                <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#loginModal" data-dismiss="modal">Войти</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signupModal" data-dismiss="modal">Регистрация</button>
            </div>
        </div>
    </div>
</div>