<?php

/**
 * This is the model class for table "instagram_content".
 *
 * The followings are the available columns in table 'instagram_content':
 * @property integer $id
 * @property string $image
 * @property string $tag
 * @property string $status
 * @property integer $date_create
 * @property integer $date_posted
 */
class Content extends CActiveRecord {

    const STATUS_NEW = 'new';
    const STATUS_POSTED = 'post';
    const STATUS_POSTED_AlBUM = 'postAlbum';
    const DEFAULT_HASH_TAG = 'vapelyfe';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'content';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date_create, date_posted', 'numerical', 'integerOnly' => true),
            array('image, status, instagram_id, group', 'length', 'max' => 255),
            array('tag', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, image, tag, status, date_create, date_posted, instagram_id, group', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if ($this->isNewRecord)
            $this->date_create = time();
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'instagram_id' => 'instagram_id',
            'image' => 'Image',
            'tag' => 'Хештеги',
            'status' => 'Статус',
            'statusText' => 'Статус',
            'date_create' => 'Дата добавления',
            'dateCreate' => 'Дата добавления',
            'date_posted' => 'Дата публикации',
            'datePosted' => 'Дата публикации',
            'group' => 'group',
            'imagesByCode' => 'Изображения',
        );
    }

    public static function getCountPublisher() {

        $criteria = new CDbCriteria;
        $criteria->addCondition('status = :statusPost OR status = :statusPostAlbum');
        $criteria->addCondition('(t.group != "" AND t.group IS NOT NULL)');

        $criteria->group = 't.group';
        $criteria->params = [':statusPost' => self::STATUS_POSTED, ':statusPostAlbum' => self::STATUS_POSTED_AlBUM];
        return self::model()->count($criteria);
    }

    public static function getCountNotPublisher() {

        $criteria = new CDbCriteria;
        $criteria->addCondition('status = :status');
        $criteria->addCondition('(t.group != "" AND t.group IS NOT NULL)');

        $criteria->group = 't.group';
        $criteria->params = [':status' => self::STATUS_NEW];
        return self::model()->count($criteria);
    }

    public function getStatusText() {
        return self::itemAlias('status', $this->status);
    }

    public function getDateCreate() {
        return Yii::app()->dateFormatter->format(Yii::app()->params->dateFormat, $this->date_create);
    }

    public function getDatePosted() {
        if (empty($this->date_posted)) {
            return '<p class="text-danger">Не опубликовано</p>';
        }
        return Yii::app()->dateFormatter->format(Yii::app()->params->dateFormat, $this->date_posted);
    }

    public function getImageUrl() {
        return CHtml::image(Yii::app()->createAbsoluteUrl('uploads/' . $this->image), '', ['class' => "img-circle img-thumbnail", 'width' => 100]);
    }

    public function getImagesByCode() {
        $criteria = new CDbCriteria;
        $criteria->compare('t.group', $this->group);
        $models = Content::model()->findAll($criteria);


        if (empty($models)) {
            return $this->imageUrl;
        }
        $data = '';
        foreach ($models as $model) {
            $data.= $model->imageUrl;
        }

        return $data;
    }

    public function getPathImage() {
        return '../../var/www/project/viper_backend/uploads/' . $this->image;
    }

    public static function getDateLastPost() {
        $criteria = new CDbCriteria;
        $criteria->addCondition('date_posted != "" OR date_posted IS NOT NULL');
        $criteria->order = 'id DESC';
        $model = Content::model()->find($criteria);
        if (empty($model)) {
            return;
        }
        return $model->datePosted;
    }

    public static function getImage($url) {
        $path_parts = pathinfo($url);
        $name = uniqid() . '.' . $path_parts['extension'];
        copy($url, Yii::getPathOfAlias('webroot') . '/uploads/' . $name);

        return $name;
    }

    public static function generateTag() {
        return '#mr_ms_vaper #mrmsvaper #mr_ms_viper #mrmsviper';
    }

    public static function getIntagramMedia($id) {
        $session = new CHttpSession;
        $session->open();
        $result = [];
        $instagram = new Instagram(array(
            'apiKey' => Yii::app()->params->instagram_client_id,
            'apiSecret' => Yii::app()->params->instagram_client_secret,
            'apiCallback' => Yii::app()->params->instagram_apiCallback
        ));
        $instagram->setAccessToken($session['instagram_access_token']);

        $data = $instagram->getMedia($id);
        if ($data->meta->code == 200) {
            $model = new Content();
            $model->instagram_id = $id;
            $model->tag = self::generateTag($data->data->tags);
            $model->status = self::STATUS_NEW;
            $model->image = self::getImage($data->data->images->standard_resolution->url);
            $model->save();
            return $id;
        }

        return;
    }

    public static function getIntagramContent($param = [], $hachtag = self::DEFAULT_HASH_TAG) {
        $session = new CHttpSession;
        $session->open();
        $result = [];
        $instagram = new Instagram(array(
            'apiKey' => Yii::app()->params->instagram_client_id,
            'apiSecret' => Yii::app()->params->instagram_client_secret,
            'apiCallback' => Yii::app()->params->instagram_apiCallback
        ));
        $instagram->setAccessToken($session['instagram_access_token']);

        $datas = $instagram->getTagMedia($hachtag, 0, $param);
        if ($datas->meta->code == 200) {
            foreach ($datas->data as $k => $data) {
                if ($data->type == 'image') {

                    $inDb = self::model()->findByAttributes(['instagram_id' => $data->id]);
                    $result['content'][$k]['url'] = $data->images->low_resolution->url;
                    $result['content'][$k]['id'] = $data->id;
                    $result['content'][$k]['inDb'] = !empty($inDb) ? true : false;
                }
            }
            $result['max_tag_id'] = $datas->pagination->next_max_id;
        }
        return $result;
    }

    public static function deleteByGroup($group) {
        $criteria = new CDbCriteria;
        $criteria->compare('t.group', $group);
        $models = Content::model()->findAll($criteria);

        foreach ($models as $model) {
            if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/' . $model->image)) {
                unlink(Yii::getPathOfAlias('webroot') . '/uploads/' . $model->image);
            }
            $model->delete();
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function searchIndex() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('tag', $this->tag, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('date_create', $this->date_create);
        $criteria->compare('date_posted', $this->date_posted);
        $criteria->addCondition('(t.group != "" AND t.group IS NOT NULL)');

        $criteria->group = 't.group';
        $criteria->order = 't.date_create DESC';


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('tag', $this->tag, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('date_create', $this->date_create);
        $criteria->compare('date_posted', $this->date_posted);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InstagramContent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function itemAlias($type, $code = NULL) {
        $_items = array(
            'status' => array(
                self::STATUS_NEW => '<p class="text-warning">Готов к публикации.</p>',
                self::STATUS_POSTED => '<p class="text-primary">Опубликован. Готов к публикации в альбом.</p>',
                self::STATUS_POSTED_AlBUM => '<p class="text-success">Опубликовано в альбом.</p>',
            )
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

}
