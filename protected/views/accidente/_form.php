

<section class="panel panel-default">
	<!--header class="panel-heading font-bold">Horizontal form</header-->
	<div class="panel-body">
		<div class="bs-example form-horizontal">
						<?php
			/* @var $this AccidenteController */
			/* @var $model Accidente */
			/* @var $form CActiveForm */
			?>
			<style>
			.rowscroll{
				overflow: auto;
				overflow-y: hidden;
				Height:?
				}
			</style>
			
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'accidente-form',
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// There is a call to performAjaxValidation() commented in generated controller code.
				// See class documentation of CActiveForm for details on this.
				'enableAjaxValidation'=>false,
			)); ?>
		
			<p class="note">Los campos que contienen <span class="required">*</span> son obligatorios.</p>
			
			<!--?php echo $form->errorSummary($model); ?-->
			<div class="col-md-5">
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'id_accidente'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'id_accidente',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'id_accidente'); ?>
				    </div>
				</div>
			
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'rut_eess'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'rut_eess',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'rut_eess'); ?>
				    </div>
				</div>
			
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'rut_trabajador'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'rut_trabajador',array('size'=>15,'maxlength'=>15, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'rut_trabajador'); ?>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'tra_cargo'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'tra_cargo',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'tra_cargo'); ?>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'tra_depto'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'tra_depto',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'tra_depto'); ?>
				    </div>
				</div>
			</div>
			<div class="col-md-6 marge">
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'acc_tipo_accidnte'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'acc_tipo_accidnte',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'acc_tipo_accidnte'); ?>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'fecha_accidente'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'fecha_accidente',array('class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'fecha_accidente'); ?>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'fecha_alta'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'fecha_alta',array('class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'fecha_alta'); ?>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-lg-4 control-label">
				    	<?php echo $form->labelEx($model,'Descripcion'); ?>
				    </div>
				    <div class="col-lg-8">
				    	<?php echo $form->textField($model,'Descripcion',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
				    	<?php echo $form->error($model,'Descripcion'); ?>
				    </div>
				</div>
			</div>
			<div class="col-lg-offset-1 col-lg-10">
 				<div class="rowscroll" id="causas"><!---->
	                <div class="panel-body">
						<hr>
	                        <div class="row" id="causes">
	                            <div class="form-group fieldGroup">
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                    <?php echo $form->labelEx($model,'acc_cbasica'); ?>
	                                   <?php echo $form->textField($model,'acc_cbasica',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
	                                    <!--<?php // $form->textField('basiccause','', array('class' => 'form-control')) ?>-->
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                    <?php // echo $form->labelEx($model, 'Descripcion'); ?> 
	                                    <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                    <a class="btn btn-success addMoreBasic" type="button">+</a>
	                                </div>
	                                <div class="form-group fieldGroupImmediate">
	                                    <div class="colo-lg-3 col-md-3 col-sm-3">
	                                        <?php echo $form->labelEx($model, 'acc_cinmediata'); ?>
	                                       <?php echo $form->textField($model,'acc_cinmediata',array('class'=>'form-control')); ?>
	                                       <!--<?php // $form->textField('immediatecause','', array('class' => 'form-control')) ?>-->
	                                    </div>
	                                    <div class="col-lg-1 col-md-1 col-sm-1">
	                                        <?php // echo $form->labelEx($model, 'Descripcion'); ?> 
	                                        <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                        <a class="btn btn-success addMoreImmediate" type="button">+</a>
	                                    </div>
	                                    <div class="form-group fieldGroupMeasure">
	                                        <div class="colo-lg-3 col-md-3 col-sm-3">
	                                            <?php echo $form->labelEx($model, 'acc_mcontrol'); ?> 
	                                            <!--<?php // $form->labelEx('controlmeasure', 'Medida de Control') ?>-->
	                                           <?php echo $form->textField($model,'acc_mcontrol',array('class'=>'form-control')); ?>
	                                           <!-- <?php // $form->textField('controlmeasure','', array('class' => 'form-control')) ?>-->
	                                        </div>
	                                        <div class="col-lg-1 col-md-1 col-sm-1">
	                                             <?php // echo $form->labelEx($model, 'Descripcion'); ?> 
	                                             <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                            <a class="btn btn-success addMoreMeasure" type="button">+</a>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <hr>
	                        <!--<a class="btn btn-danger" id="add" type="button" href="{{ route('accidents.index') }}">Descartar</a>-->
	                      
	                        <div class="row" id="basic">
	                            <div class="form-group fieldGroupBasic" style="display: none;">
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                    <?php echo $form->labelEx($model,'acc_cbasica'); ?> 
	                                    <!--<?php // $form->labelEx('basiccause', 'Causa Básica') ?>-->
	                                    <?php echo $form->textField($model,'acc_cbasica',array('class'=>'form-control')); ?>
	                                    <!--<?php // $form->text('basiccause','', array('class' => 'form-control')) ?>-->
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                    <?php // echo $form->labelEx($model,'Descripcion'); ?> 
	                                    <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                    <a class="btn btn-danger removebasic"  type="button">X</a>
	                                </div>
	                                <div class="form-group fieldGroupImmediateBasic">
	                                    <div class="colo-lg-3 col-md-3 col-sm-3">
	                                        <?php echo $form->labelEx($model,'acc_cinmediata'); ?> 
	                                        <!--<?php // $form->labelEx('immediatecause', 'Causa Inmediata') ?>-->
	                                       <?php echo $form->textField($model,'acc_cinmediata',array('class'=>'form-control')); ?>
	                                       <!--<?php // $form->textField('immediatecause','', array('class' => 'form-control')) ?>-->
	                                    </div>
	                                    <div class="col-lg-1 col-md-1 col-sm-1">
	                                        <?php // $form->labelEx($model, 'Descripcion'); ?> 
	                                        <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                        <a class="btn btn-success addMoreImmediateBasic" type="button">+</a>
	                                    </div>
	                                    <div class="form-group fieldGroupMeasureBasic">
	                                        <div class="colo-lg-3 col-md-3 col-sm-3">
	                                            <?php echo $form->labelEx($model, 'acc_mcontrol'); ?> 
	                                            <!--<?php // $form->labelEx('controlmeasure', 'Medida de Control') ?>-->
	                                            <?php echo $form->textField($model,'acc_mcontrol',array('class'=>'form-control')); ?>
	                                            <!--<?php // $form->textField('controlmeasure','', array('class' => 'form-control')) ?>-->
	                                        </div>
	                                        <div class="col-lg-1 col-md-1 col-sm-1">
	                                            <?php // $form->labelEx($model, 'Descripcion'); ?>
	                                             <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                            <a class="btn btn-success addMoreMeasureBasic" type="button">+</a>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="row" id="immediate">
	                            <div class="form-group fieldGroupImmediateAlone" style="display: none;">
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                </div>
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                    <?php echo $form->labelEx($model, 'acc_cinmediata'); ?> 
	                                    <!--<?php // $form->labelEx('immediatecause', 'Causa Inmediata') ?>-->
	                                    <?php echo $form->textField($model,'acc_cinmediata',array('class'=>'form-control')); ?>
	                                    <!--<?php // $form->textField('immediatecause','', array('class' => 'form-control')) ?>-->
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                    <?php // $form->labelEx($model, 'Descripcion'); ?> 
	                                    <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                    <a class="btn btn-danger removeimmediate"  type="button">X</a>
	                                </div>
	                                <div class="form-group fieldGroupMeasureImmediate">
	                                    <div class="colo-lg-3 col-md-3 col-sm-3">
	                                        <?php echo $form->labelEx($model, 'acc_mcontrol'); ?> 
	                                        <!--<?php // $form->labelEx('controlmeasure', 'Medida de Control') ?>-->
	                                        <?php echo $form->textField($model,'acc_mcontrol',array('class'=>'form-control')); ?>
	                                        <!--<?php // $form->textField('controlmeasure','', array('class' => 'form-control')) ?>-->
	                                    </div>
	                                    <div class="col-lg-1 col-md-1 col-sm-1">
	                                        <?php // echo $form->labelEx($model, 'Descripcion'); ?>
	                                         <!--<?php // $form->labelEx('option', 'Opción') ?>--><br>
	                                        <a class="btn btn-success addMoreMeasureImmediate" type="button">+</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="row" id="measure">
	                            <div class="form-group fieldGroupMeasureAlone" style="display: none;">
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                </div>
	                                <div class="colo-lg-3 col-md-3 col-sm-3">
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                </div>
	                                <div class="form-group fieldGroupMeasure">
	                                    <div class="colo-lg-3 col-md-3 col-sm-3">
	                                        <?php echo $form->labelEx($model, 'acc_mcontrol'); ?> 
	                                        <!--<?php //$form->labelEx('controlmeasure', 'Medida de Control') ?>-->
	                                        <?php echo $form->textField($model,'acc_mcontrol',array('class'=>'form-control')); ?>
	                                        <!--<?php //$form->textField('controlmeasure','', array('class' => 'form-control')) ?>-->
	                                    </div>
	                                    <div class="col-lg-1 col-md-1 col-sm-1">
	                                        <?php // echo $form->labelEx($model, 'Descripcion'); ?> 
	                                        <!--<?php //$form->labelEx('option', 'Opción') ?>--><br>
	                                        <a class="btn btn-danger removemeasure"  type="button">X</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                </div><!---->
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




