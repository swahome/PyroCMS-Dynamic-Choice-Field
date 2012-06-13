<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Choice Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_dynamic_choice
{	
	public $field_type_slug			= 'dynamic_choice';
	
	public $db_col_type				= 'varchar';

	public $version					= '1.1';

	public $author					= array('name'=>'Wahome', 'url'=>'http://outraider.com');

	/* Sorry I had to stick with some irrelevant names for the fields due to a possible bug in the parameter storage */
	public $custom_parameters		= array(
										'module',
										'choice_type', /* surrogate should be module_model */
										'choice_data', /* surrogate should be module_field */
										'min_choices' /* surrogate should be module_field_label */
									);

	public $plugin_return			= 'merge';
		
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($params, $entry_id, $field)
	{		
		//print_r($params); die;
		/* Set as a possible fallback for the bug that causes relevant field names not to be saved 
		if(!isset($params['custom']['choice_type'])) $params['custom']['choice_type']=$params['custom']['module']."_m";
		if(!isset($params['custom']['choice_data'])) $params['custom']['choice_data']="id";
		if(!isset($params['custom']['min_choices'])) $params['custom']['min_choices']="title";
	*/
		
		$choices = $this->_choices_to_array($params['custom']['module'],$params['custom']['choice_type'], $params['custom']['min_choices'], $params['custom']['choice_data'],$field->is_required);

		// Only put in our brs for the admin
		$line_end = (defined('ADMIN_THEME')) ? '<br />' : null;
		
		
		return form_dropdown($params['form_slug'], $choices, $params['value'], 'id="'.$params['form_slug'].'"');
	}




	// --------------------------------------------------------------------------

	/**
	 * Process before outputting
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input, $data)
	{
		$choices = $this->_choices_to_array($data['module'], $data['choice_type'], $data['min_choices'], $data['choice_data']);
		
		
		if (isset($choices[$input]) and $input != '')
		{
			return $choices[$input];
		}	
		else
		{
			return null;
		}
	}

	// --------------------------------------------------------------------------

	/**
	 * Pre-save
	 */	
	public function pre_save($input, $field)
	{
		
			return $input;
		
	}

	// --------------------------------------------------------------------------

	/**
	 * Validate input
	 *
	 * @access	public
	 * @param	string
	 * @param	string - mode: edit or new
	 * @param	object
	 * @return	mixed - true or error string
	 */
	public function validate($value, $mode, $field)
	{
		

		return true;
	}
	
	// --------------------------------------------------------------------------

	

	// --------------------------------------------------------------------------

	/**
	 * Breaks up the items into key/val for template use
	 *
	 * @access	public
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	public function pre_output_plugin($input, $params)
	{
		$options = $this->_choices_to_array($params['module'], $params['choice_type'], $params['min_choices'], $params['choice_data']);


		$this->plugin_return = 'merge';
	
		if (isset($options[$input]) and $input != '')
		{
			$choices['key']		= $input;
			$choices['val']		= $options[$input]; // legacy
			$choices['value']	= $options[$input];
			
			return $choices;
		}
		else
		{
			return null;
		}
	}
	// --------------------------------------------------------------------------
	/**
	 * Module Name
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */	
	public function param_module($value = null)
	{
		return array(
				'input' 		=> form_input('module', $value),
				'instructions'	=> $this->CI->lang->line('streams.dynamic_choice.module_instructions')
			);
	}
	// --------------------------------------------------------------------------


	/**
	 * Module Model
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */	
	public function param_choice_type($value = null)
	{
		return array(
				'input' 		=> form_input('choice_type', $value),
				'instructions'	=> $this->CI->lang->line('streams.dynamic_choice.module_model_instructions')
			);
	}
	
	
	// --------------------------------------------------------------------------

	/**
	 * Module Field in DB
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */	
	public function param_choice_data($value = null)
	{
		return array(
				'input' 		=> form_input('choice_data', $value),
				'instructions'	=> $this->CI->lang->line('streams.dynamic_choice.module_field_instructions')
			);
	}
	// --------------------------------------------------------------------------
	/**
	 * Module Field Label in DB
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */	
	public function param_min_choices($value = null)
	{
		return array(
				'input' 		=> form_input('min_choices', $value),
				'instructions'	=> $this->CI->lang->line('streams.dynamic_choice.module_field_label_instructions')
			);
	}
	/**
	 * Take a string of choices and make them into an array
	 *
	 * @access	public
	 * @param	string - raw choices data
	 * @param	string - type od choice form input
	 * @param	string - fied is required - yes or no
	 * @return	array
	 */
	public function _choices_to_array($choice_module,$choice_module_model, $choice_module_field_label='title',$choice_module_field='id', $is_required = 'no')
	{
		//get the list from the module (The module model should be named module_m and have the method get_all returning id and title as an object)
		
		//load the model
		$this->CI->load->model($choice_module.'/'.$choice_module_model);
		
		$obj_list=$this->CI->{$choice_module_model}->get_all();
		if ($is_required == 'no')
		{
			$choices[null] = get_instance()->config->item('dropdown_choose_null');
		}
		
		foreach ($obj_list as $obj)
		{
				$choices[$obj->{$choice_module_field}] = $obj->{$choice_module_field_label};
		}
		
		return $choices;
	}

}