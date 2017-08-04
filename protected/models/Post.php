<?php

/**
 * This is the model class for table "posts".
 *
 * The followings are the available columns in table 'posts':
 * @property integer $id
 * @property string $text
 * @property string $type
 * @property string $status
 * @property string $attachments
 * @property integer $date_create
 * @property integer $date_post
 */
class Post extends CActiveRecord {

    public $datetime;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'posts';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date_create, date_post', 'numerical', 'integerOnly' => true),
            array('type, status, datetime', 'length', 'max' => 255),
            array('text, attachments', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, text, type, status, attachments, date_create, date_post', 'safe', 'on' => 'search'),
        );
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
            'datetime' => 'Дата буликации',
            'text' => 'Сообщение',
            'type' => 'Тип',
            'status' => 'Статуc',
            'attachments' => 'Вложения',
            'date_create' => 'Дата когда публиковать',
            'date_post' => 'Дата публикации',
        );
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
    public function setDatePost() {
        $this->date_create = strtotime($this->datetime);
        return $this->date_create;
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('attachments', $this->attachments, true);
        $criteria->compare('date_create', $this->date_create);
        $criteria->compare('date_post', $this->date_post);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
