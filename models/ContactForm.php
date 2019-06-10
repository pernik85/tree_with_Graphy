<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{

    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['verifyCode'], 'required'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => 'site/captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Проверочный код',
        ];
    }

}
