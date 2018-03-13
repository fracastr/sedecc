<section class="panel panel-default">
	<header class="panel-heading font-bold">Buscar</header>
	<div class="panel-body">
		<div class="bs-example form-horizontal">
						<?php
			/* @var $this AccidenteController */
			/* @var $model Accidente */
			/* @var $form CActiveForm */
			?>
			
			<div class="wide form">
			
			<?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'id_accidente'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'id_accidente',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'rut_eess'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'rut_eess',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'rut_trabajador'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'rut_trabajador',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'tra_cargo'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'tra_cargo',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'tra_depto'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'tra_depto',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'acc_tipo_accidnte'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'acc_tipo_accidnte',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'fecha_accidente'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'fecha_accidente',array('class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'fecha_alta'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'fecha_alta',array('class'=>'form-control')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'Descripcion'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'Descripcion',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
					</div>
				</div>
			
						
				<div class="form-group">
					<div class="col-lg-offset-2 col-lg-10">
						<?php echo CHtml::submitButton('Buscar', array('class'=>'btn btn-sm btn-default')); ?>
					</div>
				</div>
				
			<?php $this->endWidget(); ?>
			
			</div><!-- search-form -->

		</div>
	</div>
</section>
