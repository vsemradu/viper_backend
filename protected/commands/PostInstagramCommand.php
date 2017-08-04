<?php

class PostInstagramCommand extends CConsoleCommand {

    public function actionSetGroup() { //run 1 to minute
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.status = :status');
        $criteria->params = [':status' => Content::STATUS_NEW];
        $criteria->addCondition('t.group IS NULL OR t.group = ""');
        $criteria->order = 'id';
        $criteria->limit = 400;
        $models = Content::model()->findAll($criteria);

        if (empty($models)) {
            return;
        }
        $group = uniqid();
        $start = 0;
        foreach ($models as $model) {
            $group = ($start == 4) ? uniqid() : $group;
            $model->group = $group;
            $model->save();
            $start = ($start >= 4) ? 0 : $start;
            $start++;
        }
    }

    public function actionPostImage() {
        $userAccess = UserAccess::model()->findByAttributes(['uid_id' => Yii::app()->params->vk_user_id]);

        if (empty($userAccess->id)) {
            exit('access token empty');
        }
        $criteria = new CDbCriteria;
        $criteria->compare('status', Content::STATUS_POSTED);
        $criteria->order = 'id';
        $criteria->limit = 4;
        $instagramContent = Content::model()->findAll($criteria);
        if (empty($instagramContent)) {
            exit('post empty');
        }
        $v = new Vk(array(
            'client_id' => Yii::app()->params->vk_client_id,
            'secret_key' => Yii::app()->params->vk_client_secret,
            'user_id' => Yii::app()->params->vk_user_id, // ваш номер пользователя в вк
            'scope' => ['wall', 'photos', 'video', 'offline'], // права доступа
            'access_token' => $userAccess->access_token, // права доступа
        ));

        $attachmen = [];
        foreach ($instagramContent as $content) {
            $attachmen[] = $content->pathImage;
            $content->status = Content::STATUS_POSTED_AlBUM;
            $content->save(false);
        }

        $attachments = $v->upload_photo_server(Yii::app()->params->vk_group_id, 222389346, $attachmen, false, Content::generateTag());
        print_r($attachments);
    }

    public function actionPost() {
        $criteria = new CDbCriteria;
        $criteria->compare('status', Content::STATUS_NEW);
        $criteria->order = 'id, t.group';
        $criteria->addCondition('t.group IS NOT NULL');
        $criteria->limit = 4;
        $instagramContents = Content::model()->findAll($criteria);

        if (empty($instagramContents)) {
            exit('post empty');
        }
        $userAccess = UserAccess::model()->findByAttributes(['uid_id' => Yii::app()->params->vk_user_id]);

        if (empty($userAccess->id)) {
            exit('access token empty');
        }


        $v = new Vk(array(
            'client_id' => Yii::app()->params->vk_client_id,
            'secret_key' => Yii::app()->params->vk_client_secret,
            'user_id' => Yii::app()->params->vk_user_id, // ваш номер пользователя в вк
            'scope' => ['wall', 'photos', 'video', 'offline'], // права доступа
            'access_token' => $userAccess->access_token, // права доступа
        ));
        $attachmen = [];
        foreach ($instagramContents as $content) {
            echo $content->id;
            echo '\n';
            $attachmen[] = $content->pathImage;
            $content->status = Content::STATUS_POSTED;
            $content->date_posted = time();
            $content->save();
        }

        if (!empty($attachmen)) {


            $attachments = $v->upload_photo(Yii::app()->params->vk_group_id, $attachmen);
            $response = $v->api('wall.post', array(
                'message' => Content::generateTag(),
                'attachments' => $attachments,
                'owner_id' => -Yii::app()->params->vk_group_id,
                'from_group' => 1,
            ));
        }
        return;
    }

}
