<?php

/**
 * This is the model class for table "user_access".
 *
 * The followings are the available columns in table 'user_access':
 * @property integer $id
 * @property integer $uid_id
 * @property string $access_token
 * @property integer $date_create
 * @property integer $date_end
 *
 * The followings are the available model relations:
 * @property Users $uid
 */
class UserAccess extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_access';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid_id, date_create, date_end', 'numerical', 'integerOnly' => true),
            array('access_token', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid_id, access_token, date_create, date_end', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'uid' => array(self::BELONGS_TO, 'Users', 'uid_id'),
        );
    }

    public static function getByuid($uid) {
        return self::model()->findByAttributes(['uid_id' => $uid]);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid_id' => 'Uid',
            'access_token' => 'Access Token',
            'date_create' => 'Date Create',
            'date_end' => 'Date End',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid_id', $this->uid_id);
        $criteria->compare('access_token', $this->access_token, true);
        $criteria->compare('date_create', $this->date_create);
        $criteria->compare('date_end', $this->date_end);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function parseAccessTokenUrl($url) {
        $result = [];
        $arr = explode('#', $url);
        if (empty($arr[1])) {
            return false;
        }
        $arr = explode('&', $arr[1]);
        if (count($arr) == 0) {
            return false;
        }

        foreach ($arr as $d) {
            $b = explode('=', $d);
            $result[$b[0]] = $b[1];
        }
        if (empty($result['access_token'])) {
            return false;
        }
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserAccess the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
