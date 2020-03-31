<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use GPH\Api\DefaultApi;
use app\models\ContactForm;

class SiteController extends Controller
{
    private $items;
    private $treeItems = array();
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => null,
//                'testLimit' => 1,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $items = array();
        $intMax = rand(100, 1000);
        for ($i = 0; $i <= $intMax; $i++){
            $items[$i]['val'] = $i;
            $items[$i]['parent'] = $this->getParent($i, $intMax);
        }

        $this->items = $items;

        while (count($this->items) > 0 ){
            $parents = array_column($this->items, 'parent');
            $minParents = min($parents);

            $this->treeItems = array_merge($this->treeItems, $this->makeTree($this->items, $minParents));
        }

        return $this->render('index', array('items' => $this->treeItems));
    }

    /**
     * возращает дерево из N елементов
     * @param $items
     * @param int $parent
     * @return array
     */
    private function makeTree($items, $parent = 0) {
        // будем строить новый массив-дерево
        $nitems = array();
        foreach($items as $ki => $item) {
            /* проверяем, относится ли родитель элемента к самому
            верхнему уровню и не ссылается ли на самого себя */
            if($item['parent'] == $parent) {
                // удаляем этот элемент из общего массива
                unset($this->items[$ki]);
                $item['children'] = $this->makeTree($this->items, $item['val']);
                $nitems[$ki] = $item;


            }
        }
        return $nitems;
    }

    /**
     * возращает рандомного родителя
     * @param $numberItem - текущий елемент
     * @param $intMax - максимальное число елементов в дереве
     * @return int|mixed
     */
    private function getParent($numberItem, $intMax){
        return ($parent = rand(0, $intMax)) != $numberItem ? $parent : $this->getParent($numberItem, $intMax);
    }

    /**
     * Форма капчи
     * @return string
     */
    public function actionFormCaptcha()
    {

        $model = new ContactForm();
        echo $this->renderPartial('_form_captcha', [
            'model' => $model
        ]);
        die();
    }

    /**
     * Получаем случайную картинку из https://giphy.com/
     */
    public function actionGiphy(){
        $model = new ContactForm();
        $csrfToken = Yii::$app->request->post('csrf_token');
        $secretKey = Yii::$app->session['csrf_captcha_form'];
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post()) && $model->validate() && $csrfToken == $secretKey) {
            try {
                $apiInstance = new DefaultApi();
                $result = $apiInstance->gifsRandomGet(Yii::$app->params['giphy_api_key'])->getData();
                echo json_encode(array('status' => 'ok', 'content' => $result["image_url"]));
                die();
            } catch (Exception $e) {
                echo 'Ошибка при выводе картинки Giphy: ', $e->getMessage(), PHP_EOL;
            }
        }else{
            var_dump($model->getErrors());die();
        }
    }
}
