<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_lib
{

    private $allModules = array();
    private $allPatientModules = array();
    protected $modules;
    public $perm_category;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->modules = array();
        $this->patientModules = array();
        self::loadModule();
        self::loadPatientModule();
        // prd($this->modules['lab']);
    }

    public function loadModule()
    {
        $this->allModules = $this->CI->module_model->get();

        if (!empty($this->allModules)) {
            foreach ($this->allModules as $mod_key => $mod_value) {

                if ($mod_value->is_active == 1) {
                    $this->modules[$mod_value->short_code] = true;
                } else {

                    $this->modules[$mod_value->short_code] = false;
                }
            }
        }
    }

    public function loadPatientModule()
    {
        $this->allPatientModules = $this->CI->module_model->getPatientModule();

        if (!empty($this->allPatientModules)) {
            foreach ($this->allPatientModules as $mod_key => $mod_value) {

                if ($mod_value->is_active == 1) {
                    $this->patientModules[$mod_value->short_code] = true;
                } else {

                    $this->patientModules[$mod_value->short_code] = false;
                }
            }
        }
    }

    public function hasActive($module = null)
    {

        if ($this->modules[$module]) {
            return true;
        }

        return false;
    }

    public function hasPatientActive($module = null)
    {
        if ($this->patientModules[$module]) {
            return true;
        }
        return false;
    }

}