<?php

/**
 * This is the model class for table "min_accidente".
 *
 * The followings are the available columns in table 'min_accidente':
 * @property string $id_accidente
 * @property string $rut_eess
 * @property string $rut_trabajador
 * @property string $tra_cargo
 * @property string $tra_depto
 * @property string $acc_tipo_accidnte
 * @property string $fecha_accidente
 * @property string $fecha_alta
 * @property string $Descripcion
 */
class Accidente extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'min_accidente';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_accidente, rut_eess, rut_trabajador, fecha_accidente, Descripcion', 'required'),
			array('id_accidente, rut_eess, rut_trabajador', 'length', 'max'=>15),
			array('tra_cargo, tra_depto, acc_tipo_accidnte, Descripcion', 'length', 'max'=>255),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_accidente, rut_eess, rut_trabajador, tra_cargo, tra_depto, acc_tipo_accidnte, fecha_accidente, fecha_alta, Descripcion', 'safe', 'on'=>'search'),
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
			'id_accidente' => 'Id Accidente',
			'rut_eess' => 'Rut Eess',
			'rut_trabajador' => 'Rut Trabajador',
			'tra_cargo' => 'Cargo',
			'tra_depto' => 'Depto',
			'acc_tipo_accidnte' => 'Tipo Accidnte',
			'fecha_accidente' => 'Fecha Accidente',
			'fecha_alta' => 'Fecha Alta',
			'Descripcion' => 'Descripcion',
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

		$criteria->compare('id_accidente',$this->id_accidente,true);
		$criteria->compare('rut_eess',$this->rut_eess,true);
		$criteria->compare('rut_trabajador',$this->rut_trabajador,true);
		$criteria->compare('tra_cargo',$this->tra_cargo,true);
		$criteria->compare('tra_depto',$this->tra_depto,true);
		$criteria->compare('acc_tipo_accidnte',$this->acc_tipo_accidnte,true);
		$criteria->compare('fecha_accidente',$this->fecha_accidente,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('Descripcion',$this->Descripcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Accidente the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
