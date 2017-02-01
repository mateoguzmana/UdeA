<?php

/**
 * This is the model class for table "Links".
 *
 * The followings are the available columns in table 'Links':
 * @property integer $IdLink
 * @property integer $IdMenu
 * @property string $Description
 * @property integer $Sequence
 * @property string $Controller
 */
class Links extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IdMenu, Description, Sequence, Controller', 'required'),
			array('IdMenu, Sequence', 'numerical', 'integerOnly'=>true),
			array('Description, Controller', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('IdLink, IdMenu, Description, Sequence, Controller', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'IdLink' => 'Id Link',
			'IdMenu' => 'Id Menu',
			'Description' => 'Description',
			'Sequence' => 'Sequence',
			'Controller' => 'Controller',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('IdLink',$this->IdLink);
		$criteria->compare('IdMenu',$this->IdMenu);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Sequence',$this->Sequence);
		$criteria->compare('Controller',$this->Controller,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Links the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function findProfile($controller, $idProfile) {
		$connection = Yii::app()->db;
                $sql = "SELECT action.Description FROM `Links` link INNER JOIN `ProfileAdministration` profile ON profile.IdLink=link.IdLink INNER JOIN `Actions` action ON action.IdAction=profile.IdAction WHERE link.Controller='$controller' AND profile.IdProfile='$idProfile';";
                $command = $connection->createCommand($sql);
        	$dataReader = $command->queryAll();
        	return $dataReader;
        }

}
