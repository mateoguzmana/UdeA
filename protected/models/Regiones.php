<?php

/**
 * This is the model class for table "Regiones".
 *
 * The followings are the available columns in table 'Regiones':
 * @property integer $IdRegion
 * @property string $Nombre
 * @property integer $CodEleccion
 *
 * The followings are the available model relations:
 * @property Candidatos[] $candidatoses
 * @property Elecciones $codEleccion
 */
class Regiones extends CActiveRecord {

    public $Eleccion;
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Regiones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Nombre, CodEleccion', 'required'),
            array('CodEleccion', 'numerical', 'integerOnly' => true),
            array('Nombre', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('Nombre, Eleccion', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'candidatoses' => array(self::HAS_MANY, 'Candidatos', 'CodRegion'),
            'codEleccion' => array(self::BELONGS_TO, 'Elecciones', 'CodEleccion'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdRegion' => 'Id Region',
            'Nombre' => 'Nombre Región',
            'CodEleccion' => 'Cod Eleccion',
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
        $criteria->together = true;
        $criteria->with = array('codEleccion');
        $criteria->compare('IdRegion', $this->IdRegion);
        $criteria->compare('Nombre', $this->Nombre, true);
        $criteria->compare('CodEleccion', $this->CodEleccion);
        $criteria->compare('codEleccion.Descripcion',$this->Eleccion, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Regiones the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
