<section class="panel panel-default">
	<!--header class="panel-heading font-bold">Horizontal form</header-->
	<div class="panel-body">
		<div class="bs-example form-horizontal">
						<?php
			/* @var $this FormulariosController */
			/* @var $model Formularios */
			/* @var $form CActiveForm */
			?>

			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'formularios-form',
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// There is a call to performAjaxValidation() commented in generated controller code.
				// See class documentation of CActiveForm for details on this.
				'enableAjaxValidation'=>false,
			)); ?>

			<p class="note">Los campos que contienen <span class="required">*</span> son obligatorios.</p>

			<!--?php echo $form->errorSummary($model); ?-->
			<div class="form-group">
				<div class="col-lg-2 control-label">
						<?php echo $form->labelEx($model,'eess_rut'); ?>
					</div>
					<div class="col-lg-10">
						<?php $disabled = false; if(Yii::app()->controller->usertype() == 1) $disabled = true;?>

						<?php echo $form->dropDownList($model,'eess_rut',CHtml::listData(Eess::model()->findAll('eess_estado=1'), 'eess_rut', 'eess_nombre_corto'),array('prompt'=>' ','class'=>'form-control bord','disabled'=>$disabled));?>
						<?php //echo $form->textField($model,'eess_rut',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
						<?php echo $form->error($model,'eess_rut'); ?>
					</div>
			</div>

			<div class="form-group">
					<div class="col-lg-2 control-label">
						  <?php echo $form->labelEx($model,'tipo_checklist'); ?>
					</div>
					<div class="col-lg-10">
							<?php echo $form->dropDownList($model,'tipo_checklist',CHtml::listData(Pregunta::model()->findAll("eess_rut = '".$model->eess_rut."'"), 'tipo_checklist', 'tipo_checklist'),array('prompt'=>' ','class'=>'form-control bord'));?>
						  <!-- <?php //echo $form->textField($model,'tipo_checklist',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?> -->
							<?php echo $form->error($model,'tipo_checklist'); ?>
					</div>
			</div>
			<?php
			if(!$model->isNewRecord){
				echo '
				<div class="form-group">
					<div class="col-lg-2 control-label">'.$form->labelEx($model,'checklist').'</div>
				    <div class="col-lg-10">'.$form->dropDownList($model,'checklist',CHtml::listData(Yii::app()->db->createCommand("SELECT * FROM min_pregunta")->query()->readAll(), 'car_id', 'car_id'),array('class'=>'form-control bord')).
						'</div>
				</div>';
			}else{
				echo '<div class="form-group">
					<div class="col-lg-2 control-label">'.$form->labelEx($model,'checklist').'</div>
				    <div class="col-lg-10">'.$form->dropDownList($model,'checklist',CHtml::listData(Yii::app()->db->createCommand("SELECT * FROM min_pregunta")->query()->readAll(), 'car_id', 'car_id'),array('class'=>'form-control bord')).
						'</div>
				</div>';
			}
			?>
<!--<div class="form-group">
		<div class="col-lg-2 control-label">'.$form->labelEx($model,'checklist').'
			</div>
			<div class="col-lg-10">
				'.$form->textField($model,'checklist',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')).'
				'.$form->error($model,'checklist').'
			</div>
	</div>';
-->

		<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'correlativo_chk_eess'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'correlativo_chk_eess',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'correlativo_chk_eess'); ?>
			    </div>
			</div>






						<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'n_campos'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'n_campos',array('class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'n_campos'); ?>
			    </div>
			</div>

						<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'campo'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'campo',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'campo'); ?>
			    </div>
			</div>

						<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'nombre_campos'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'nombre_campos',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'nombre_campos'); ?>
			    </div>
			</div>

						<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'campos_values'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'campos_values',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'campos_values'); ?>
			    </div>
			</div>

						<div class="form-group">
				<div class="col-lg-2 control-label">
			    	<?php echo $form->labelEx($model,'campos_requeridos'); ?>
			    </div>
			    <div class="col-lg-10">
			    	<?php echo $form->textField($model,'campos_requeridos',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			    	<?php echo $form->error($model,'campos_requeridos'); ?>
			    </div>
			</div>

			<div class="row">
				<div class="col-sm-12">


					<script>

					</script>

					<table id="items" class="table table-collapse">

						<?php

						$array_equipos = array("eq_codigo", "eq_maquina",
					 "eq_marca", "eq_modelo", "eva_operador", "eva_apr", "eva_supervisor",
					 "eva_jefe_faena", "eva_faena", "eva_horometro", "eva_ot");
					 $array_trabajadores = array("eq_codigo", "eq_maquina",
						"eq_marca", "eq_modelo", "eva_operador", "eva_apr", "eva_supervisor",
						"eva_jefe_faena", "eva_faena", "eva_horometro", "eva_ot");
						$array_isntalaciones = array("eq_codigo", "eq_maquina",
						 "eq_marca", "eq_modelo", "eva_operador", "eva_apr", "eva_supervisor",
						 "eva_jefe_faena", "eva_faena", "eva_horometro", "eva_ot");
						 ?>

						<table class="col-sm-12">
							<thead>
								<tr>
									<td>Nombre Campos</td>
									<td>Campos Values usado?</td>
									<td>Requeridos</td>
								</tr>
								<tr>
									<td>-</td>
									<td>- </td>
									<td> -</td>
								</tr>
							</thead>
							<tbody>
								<!-- TOMAMOS LOS VALORES SEGUN LOS CHECKLIST -->




			<?php
			for($i=0;$i<count($array_equipos);$i++)
			{
				echo'
				<tr>
					<td>
						<small>'.$array_equipos[$i].'</small>
						<input type="text" name="" value="'.$array_equipos[$i].'">
					</td>
					<td>
						<input required id="usosi" name="uso" type="radio" value="si">
						<small>si</small>

						<input required id="usono" name="uso" type="radio" value="no">
						<small>no</small>
					</td>
					<td>
						<input required id="reqsi" name="req" type="radio" value="si">
						<small>si</small>

						<input required id="reqno" name="req" type="radio" value="no">
						<small>no</small>
					</td>
				</tr>';
			}
			?>

							</tbody>

						</table>
					</table>
					<script>
					function mostraredicion(id){
						cerrar();
						document.getElementById('edicion'+id).style.display = 'block';
					}
					function cerrar(){
						elems = document.getElementsByClassName('ediciones');
						for(i=0;i<elems.length;i++){
							elems[i].style.display = 'none';
						}
					}
					</script>
					<script>
					var prueba;
					//aqui debemos ingresar los datos que se tomaran y se cargaran por cada check es decir:
					//al seleccionar una empresa cargar los datos de los tipo de checklist checklist que corresponden
					</script>
				</div>
			</div>

						<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Guardar', array('class'=>'btn btn-sm btn-default')); ?>
				</div>
			</div>
			<?php $this->endWidget(); ?>
    	</div>
	</div>
</section>
