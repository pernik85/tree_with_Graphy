<?= $this->render('_tree', array('items' => $items))?>

<span class="node" style="cursor:pointer">xasxsaxaxaxaxaxxxxxxxxxxxxxxxxxxxxxxxxxxx</span>
<?php
    $script = "
    $('#ttt').modal('toggle');
        $('.node').on('click', function(){
            $.ajax({
                url: '/web/site/form-captcha',
                success: function(res){
                    $('#form_captcha').html(res);
                    $('#modal-captcha').modal('toggle');
                },
                error: function(){
                    alert(\"Error!\");
                }
            });               
        });               
    ";
$this->registerJs($script);
    ?>

<div class="modal" id="modal-captcha" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="form_captcha">
            </div>
        </div>
    </div>
</div>
