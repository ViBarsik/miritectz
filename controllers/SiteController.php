<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\credit\CreditHandler;
use app\models\install\Generator;
use app\models\logic\LogicHandler;

class SiteController extends Controller
{
    public function beforeAction($action)
    {
        if(!isset(Yii::$app->params['isInstall']) && $this->action->id !== 'install'){
            return $this->redirect('/install');
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogica() {       
        $handler = new LogicHandler();
        
        return $this->render('logic', $handler->getViewData());
    }

    public function actionDebug() {
        return $this->render('debug');
    }

    public function actionTask() {        
        $handler = new CreditHandler();
        
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax){
            $Credit = $handler->createNewCredit();
            if($Credit->hasErrors()){
                return $this->renderPartial('credit/form',['Credit' => $Credit]);
            }            
            return $this->renderPartial('credit/table',['Credit' => $Credit]);
        } 
        
        return $this->render('credit/credit',['Credit' => $handler->getCreditObject('create')]);
    }


    public function actionInstall() {
        $this->layout = 'install';
        if(isset(Yii::$app->params['isInstall'])){
            return $this->redirect('/');
        }

        try{
            Generator::createTables();
            Generator::updateConfig();

            return $this->redirect('/');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
