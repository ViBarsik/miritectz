<?php
namespace app\controllers;

use app\models\logic\LogicHandler;
use Yii;

use app\models\user\UserHandler;

class SiteController extends \yii\web\Controller
{
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogica() {
        if(Yii::$app->request->isPost){
            $this->refresh();
        }
        
        $handler = new LogicHandler();

        return $this->render('logic', $handler->getViewData());
    }

    public function actionDebug() {
        return $this->render('debug');
    }

    public function actionTask() {
        return $this->render('task');
    }
    
    
    
    
    
    
    
    
    
    
    

    public function actionRegistration() {
        $UserHandler = new UserHandler();
        $User = $UserHandler->getUser('create');

        if(Yii::$app->request->isPost && $UserHandler->registration()){
            return $this->redirect('/login');
        }

        return $this->render('registration', [
            'User' => $User,
        ]);
    }

    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $UserHandler = new UserHandler();
        $User = $UserHandler->getUser('login');

        if(Yii::$app->request->isPost && $UserHandler->login()){
            return $this->goBack();
        }

        return $this->render('login', ['User' => $User,]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('registration', [
            'model' => $model,
        ]);
    }
}
