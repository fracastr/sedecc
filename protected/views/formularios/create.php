<?php
/* @var $this FormulariosController */
/* @var $model Formularios */

$this->breadcrumbs=array(
	'Formularioses'=>array('index'),
	'Nuevo',
);
?>


<span style='float:right;'>
<?php echo CHtml::link('<i class="i i-list"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-list2"></i>',array('admin'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
</span>
<h1>Nuevo Formularios</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>