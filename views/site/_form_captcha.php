<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>
<?php
    $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
    Yii::$app->session['csrf_captcha_form'] = $csrfToken;
?>
<?php $form = ActiveForm::begin([
    'id' => 'captcha-form'
]);?>

    <div id="captcha">
        <?= $form->field($model, 'verifyCode')->widget(yii\captcha\Captcha::class, [
            'options' => [
                'placeholder' => true
            ],
            'imageOptions' => [
                'id' => 'my-captcha-image'
            ]
        ]); ?>
        <?= Html::hiddenInput('csrf_token', $csrfToken); ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', [
                'class' => 'btn btn-primary submit-form',
            ]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<script>
    $(".submit-form").on("click", function(e){
        e.preventDefault();
        $.ajax({
            type: 'post',
            dataType : "json",
            url: "/web/site/giphy",
            data: $("#captcha-form").serialize(),
            success: function(res){
                console.log(res);
                if(res.status == 'ok'){
                    $("#captcha").html("<img src=\'"+res.content+"\'>");
                }
            },
            error: function(){
                alert("Error!");
            }
        }, 'json');
    });
    $.ajax({
        type: 'GET',
        url: '/web/site/captcha',
        data: {refresh: 1},
        success: function (data) {
            $("img[id$='-captcha-image']").attr('src', data.url);
        }
    });
</script>

