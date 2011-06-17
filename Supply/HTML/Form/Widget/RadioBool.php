<?php

class EP_HTML_Form_Widget_RadioBool
{
	
	public $name = '';
	public $positiveLabel = 'Yes';
	public $positiveValue = 1;
	public $positiveChecked = 0;
	public $negativeLabel = 'No';
	public $negativeValue = 0;
	public $negativeChecked = 0;
	public $dependentSectionLabel;
	
	function __construct( $name, $checked = null )
	{
		$this->name = $name;
		if ( !is_null( $checked ))
		{
			if ( $checked == 1 )
			{
				$this->positiveChecked = 1;
			}
			else
			{
				$this->negativeChecked = 1;
			}
		}
	}
	
	public function setDependentSectionLabel( $name = '' )
	{
		if ( $name == '' )
		{
			$this->dependentSectionLabel = $this->name . '_section_' . strtolower( $this->positiveLabel );
		}
	}
	
	
	public function getOutput()
	{
		$output = '';
		$positiveId = strtolower("{$this->name}_{$this->positiveLabel}" );
		$positiveChecked = '';
		$negativeChecked = '';
		if ( $this->positiveChecked )
		{
			$positiveChecked = " checked ";
		}
		if ( $this->negativeChecked )
		{
			$negativeChecked = " checked ";
		}
		$negativeId = strtolower("{$this->name}_{$this->negativeLabel}" );
		$output = <<<EOL
		<!-- auto generated from RadioBool -->
		<input name="{$this->name}" id="{$positiveId}" style="margin-left: 1em;" type="radio" value="{$this->positiveValue}" {$positiveChecked} ><label for="{$positiveId}">{$this->positiveLabel}</label><input name="{$this->name}" id="{$negativeId}" style="margin-left: 1em;" type="radio" value="{$this->negativeValue}" {$negativeChecked} ><label for="{$negativeId}">{$this->negativeLabel}</label>	
EOL;
		return $output;
	}
}

