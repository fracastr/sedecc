<section class="panel panel-default">
	<header class="panel-heading font-bold">Buscar</header>
	<div class="panel-body">
		<div class="bs-example form-horizontal">
						<?php
			/* @var $this EessController */
			/* @var $model Eess */
			/* @var $form CActiveForm */
			?>
			
			<div class="wide form">
			
			<?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_rut'); ?>
					</div>
					<div class="col-lg-2">
						<?php echo $form->numberField($model,'eess_rut',array('size'=>20,'maxlength'=>20, 'class'=>'form-control bord')); ?>
					</div>
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_nombre_corto'); ?>
					</div>
					<div class="col-lg-6">
						<?php echo $form->textField($model,'eess_nombre_corto',array('size'=>60,'maxlength'=>150, 'class'=>'form-control bord')); ?>
					</div>
				</div>
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_razon_social'); ?>
					</div>
					<div class="col-lg-10">
						<?php echo $form->textField($model,'eess_razon_social',array('size'=>60,'maxlength'=>250, 'class'=>'form-control bord')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_ciudad'); ?>
					</div>
					<div class="col-lg-2">
						<?php echo $form->textField($model,'eess_ciudad',array('size'=>60,'maxlength'=>200, 'class'=>'form-control bord')); ?>
					</div>
					<div class="col-lg-1 control-label">
						<?php echo $form->labelEx($model,'eess_telefono'); ?>
					</div>
					<div class="col-lg-3">
						<?php echo $form->numberField($model,'eess_telefono',array('size'=>60,'maxlength'=>200, 'class'=>'form-control bord')); ?>
					</div>
					<div class="col-lg-1 control-label">
						<?php echo $form->labelEx($model,'eess_email'); ?>
					</div>
					<div class="col-lg-3">
						<?php echo $form->emailField($model,'eess_email',array('size'=>60,'maxlength'=>250, 'class'=>'form-control bord')); ?>
					</div>
				</div>
			
					
			
										
				<div class="form-group">
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_creado'); ?>
					</div>
					<div class="col-lg-2">
						<?php echo $form->dateField($model,'eess_creado',array('class'=>'form-control bord')); ?>
					</div>
					<div class="col-lg-1 control-label">
						<?php echo $form->labelEx($model,'eess_estado'); ?>
					</div>
					<div class="col-lg-3">
						<?php echo $form->dropDownList($model,'eess_estado',array('1'=>'Activo','0'=>'Inactivo'),array('class'=>'form-control bord'));?>
						<!--?php echo $form->textField($model,'eess_estado',array('size'=>20,'maxlength'=>20, 'class'=>'form-control')); ?-->
					</div>
				</div>
			
				<h3 class="page-header">Información de representante</h3>
										
				<div class="form-group">
					<div class="col-lg-3 control-label">
						<?php echo $form->labelEx($model,'eess_representante'); ?>
					</div>
					<div class="col-lg-9">
						<?php echo $form->textField($model,'eess_representante',array('size'=>60,'maxlength'=>200, 'class'=>'form-control bord')); ?>
					</div>
				</div>
			
										
				<div class="form-group">
					<div class="col-lg-3 control-label">
						<?php echo $form->labelEx($model,'eess_representante_telefono'); ?>
					</div>
					<div class="col-lg-3">
						<?php echo $form->numberField($model,'eess_representante_telefono',array('size'=>60,'maxlength'=>200, 'class'=>'form-control bord')); ?>
					</div>
					<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_representante_email'); ?>
					</div>
					<div class="col-lg-4">
						<?php echo $form->emailField($model,'eess_representante_email',array('size'=>60,'maxlength'=>255, 'class'=>'form-control bord')); ?>
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
