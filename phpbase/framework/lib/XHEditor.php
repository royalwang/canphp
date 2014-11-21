<?php
/**
 * XHEditor class file.
 *
 * @author jerry2801 <jerry2801@gmail.com>
 * <pre>
 * $this->widget('application.extensions.XHEditor',array(
 *     'model'=>$model,
 *     'attribute'=>'content',
 *     'htmlOptions'=>array('cols'=>80,'rows'=>20,'style'=>'width: 100%; height: 500px;'),
 * ));
 * </pre>
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

class XHEditor extends CJuiInputWidget
{
    public function run()
    {
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeTextArea($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textArea($name,$this->value,$this->htmlOptions);

        $path=dirname(__FILE__).DIRECTORY_SEPARATOR.'xheditor';
        $baseUrl=Yii::app()->getAssetManager()->publish($path);

        $js='jQuery(\'#'.$id.'\').xheditor('.$this->options.');';

		$cs=Yii::app()->getClientScript();
        Yii::app()->clientScript->registerCoreScript('jquery');

        $cs->registerScriptFile($baseUrl.'/xheditor.min.js');

		$cs->registerScript(__CLASS__.'#'.$id,$js);
    }
}