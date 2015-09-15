<?php
/**
 * Class following Singleton pattern for specific extension configuration
 *
 * @author Jan-Derek (CiviCooP) <j.vos@bosqom.nl>
 */
class CRM_Lidmaatschapbijdragerapport_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;
    
  protected $_lidmaatschap_custom_group_id = 0;
  protected $_lidmaatschap_custom_group = array();
  protected $_lidmaatschap_custom_fields = array();
  protected $_lidmaatschap_custom_fields_by_name = array();

  /**
   * Constructor
   */
  function __construct() {
    $this->set_lidmaatschap_custom_group_id();
    $this->set_lidmaatschap_custom_group();
    $this->set_lidmaatschap_custom_fields();
  }
  
  /**
   * Function to return singleton object
   * 
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Lidmaatschapbijdragerapport_Config();
    }
    return self::$_singleton;
  }
  
  /*
   * Set lidmaatschap custom group id
   * The id is to get all the custom fields
   */
  protected function set_lidmaatschap_custom_group_id() {
    try {
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'name' => 'Lidmaatschap__Maatschappij',
      );
      $result = civicrm_api('CustomGroup', 'getsingle', $params);     
      $this->_lidmaatschap_custom_group_id = $result['id'];
      
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find lidmaatschap historie custom group id, '
        . 'error from API CustomGroup getsingle: '.$ex->getMessage());
    }
  }
  
  /*
   * Get lidmaatschap custom group id
   */
  public function get_lidmaatschap_custom_group_id() {
    return $this->_lidmaatschap_custom_group_id;    
  }
  
  /*
   * Set lidmaatschap custom group
   */
  protected function set_lidmaatschap_custom_group() {
    try {
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'id' => $this->_lidmaatschap_custom_group_id,
      );
      $result = civicrm_api('CustomGroup', 'getsingle', $params);     
      $this->_lidmaatschap_custom_group = $result;
      
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find lidmaatschap historie custom group, '
        . 'error from API CustomGroup getsingle: '.$ex->getMessage());
    }
  }
  
  /*
   * Get lidmaatschap custom group
   */
  public function get_lidmaatschap_custom_group() {
    return $this->_lidmaatschap_custom_group;    
  }

  
  /*
   * Set lidmaatschap custom fields
   * Get all the custom fields belongs to lidmaatschap
   */
  protected function set_lidmaatschap_custom_fields() {
    try {
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'custom_group_id' => $this->_lidmaatschap_custom_group_id,
        'is_active' => '1',
      );
      $result = civicrm_api('CustomField', 'get', $params);  
            
      foreach ($result['values'] as $key => $array){
        $this->_lidmaatschap_custom_fields[$array['id']] = array(
          'id' => $array['id'],
          'name' => $array['name'],
          'label' => $array['label'],
          'column_name' => $array['column_name'],
        );
        
        $this->_lidmaatschap_custom_fields_by_name[$array['name']] = array(
          'id' => $array['id'],
          'name' => $array['name'],
          'label' => $array['label'],
          'column_name' => $array['column_name'],
        );
        
        if(isset($array['option_group_id']) and !empty($array['option_group_id'])){
          $this->_lidmaatschap_custom_fields[$array['id']]['option_group_id'] = $array['option_group_id'];
          $this->_lidmaatschap_custom_fields_by_name[$array['name']]['option_group_id'] = $array['option_group_id'];
        }
      }
      
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find lidmaatschap historie custom fields, '
        . 'error from API CustomField get: '.$ex->getMessage());
    }
  }
  
  /*
   * Get lidmaatschap custom fields
   */
  public function get_lidmaatschap_custom_fields() {
    return $this->_lidmaatschap_custom_fields;
  }
  
  /*
   * Get lidmaatschap custom fields by name
   */
  public function get_lidmaatschap_custom_fields_by_name() {
    return $this->_lidmaatschap_custom_fields_by_name;
  }
}