<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>
?>

<?php echo "
<span style='float:right;'>
<?php echo CHtml::link('<i class=\"i i-list\"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class=\"i i-plus2\"></i>',array('create'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class=\"i i-eye\"></i>',array('view','id'=>\$model->".$this->tableSchema->primaryKey."),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class=\"i i-cross2\"></i>',array('trash','id'=>\$model->".$this->tableSchema->primaryKey."),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default','onclick'=>'return confirm(\'¿Realmente desea eliminar?\');'));?>
<?php echo CHtml::link('<i class=\"i i-list2\"></i>',array('admin'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
</span> ";?>

<h1>Modificar <?php echo $this->modelClass." <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>