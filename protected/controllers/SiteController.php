<?php

class SiteController extends Controller {

    public function actionAjaxAddPostFromInstagram() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->request->getPost('id');
            $result = Content::getIntagramMedia($id);
            echo CJSON::encode(['code' => 200, 'result' => $result]);
            return;
        }
        echo CJSON::encode(['code' => 500, 'result' => '']);
        return;
    }

    public function actionAjaxInstagram() {

        if (Yii::app()->request->isAjaxRequest) {

            $max_tag_id = Yii::app()->request->getPost('max_tag_id');
            $tag = Yii::app()->request->getPost('tag', Content::DEFAULT_HASH_TAG);

            if (!empty($max_tag_id)) {

                $result = Content::getIntagramContent(['max_tag_id' => $max_tag_id], $tag);

                $data['content'] = $this->renderPartial('_view_instagram', ['result' => $result], true, true);
                $data['max_tag_id'] = $result['max_tag_id'];
                echo CJSON::encode(['code' => 200, 'result' => $data]);
                return;
            }
            echo CJSON::encode(['code' => 500, 'result' => '']);
            return;
        }
        echo CJSON::encode(['code' => 500, 'result' => '']);
        return;
    }

    

    public function actionInstagram() {
        $tag = Yii::app()->request->getParam('tag', Content::DEFAULT_HASH_TAG);
        $instagram = new Instagram(array(
            'apiKey' => Yii::app()->params->instagram_client_id,
            'apiSecret' => Yii::app()->params->instagram_client_secret,
            'apiCallback' => Yii::app()->params->instagram_apiCallback
        ));
        $result = [];

        $session = new CHttpSession;
        $session->open();

        if (!empty($session['instagram_access_token'])) {


            $result = Content::getIntagramContent([], $tag);
        }
        if (!empty($_GET['code'])) {
            $code = $_GET['code'];
            $data = $instagram->getOAuthToken($code);

            if (!empty($data->access_token)) {
                $session = new CHttpSession;
                $session->open();
                $session['instagram_access_token'] = $data->access_token;
                $session['instagram_id'] = $data->user->id;
                Yii::app()->user->setFlash('success', "Вы успешно авторизировались");
                return $this->redirect('/site/instagram');
            } else {
                return $this->redirect(Yii::app()->homeUrl);
            }


            return $this->redirect(Yii::app()->homeUrl);
        }



        $this->render('instagram', [
            'tag' => $tag,
            'instagram' => $instagram,
            'result' => $result,
            'instagram_access_token' => $session['instagram_access_token'],
        ]);
    }

    public function actionIndex() {


        $this->render('index');
    }

    public function actionGetVkCode() {
        $v = new Vk(array(
            'client_id' => Yii::app()->params->vk_client_id,
            'secret_key' => Yii::app()->params->vk_client_secret,
            'user_id' => Yii::app()->params->vk_user_id,
            'scope' => ['wall', 'photos', 'video', 'offline'],
        ));
        if (!empty(Yii::app()->request->getParam('code'))) {
            $responce = $v->get_access_token(Yii::app()->request->getParam('code'));
            if (!empty($responce['error'])) {
                Yii::app()->user->setFlash('warning', "Ошибка данных");
                return $this->redirect('/site/getVkCode');
            }
            $model = UserAccess::getByuid($responce['user_id']);
            if (empty($model)) {
                $model = new UserAccess;
            }
            $model->uid_id = $responce['user_id'];
            $model->access_token = $responce['access_token'];
            $model->date_create = time();
            $model->save();
            Yii::app()->user->setFlash('success', "Вы успешно авторизировались");
            $this->redirect(Yii::app()->homeUrl);
        }
        $this->render('getVkCode', ['v' => $v]);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin() {
        if (!empty(Yii::app()->request->getParam('uid'))) {
            $user = User::getByuid(Yii::app()->request->getParam('uid'));
            if (empty($user->id)) {
                $user = new User;
                $user->attributes = $_GET;
                $user->save();
            }
            $identity = new UserIdentity(Yii::app()->request->getParam('uid'));
            $identity->authenticate();
            Yii::app()->user->login($identity, 3600 * 24 * 7);
            Yii::app()->user->setFlash('success', "Вы успешно авторизировались");
            $this->redirect(Yii::app()->homeUrl);
        }
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
