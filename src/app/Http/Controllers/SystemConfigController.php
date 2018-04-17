<?php

namespace Cord\Settings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Backpack\CRUD\PanelTraits\Access;
use Cord\Settings\Repository;

class SystemConfigController extends Controller
{
    use Access;
    private $access = ['list', 'update'];
    private $data;

    public function __construct() {
      $this->data['singular'] = ucfirst(trans('cord::settings.setting_singular'));
      $this->data['plural'] = ucfirst(trans('cord::settings.setting_plural'));
      $this->data['route'] = 'settings/system';
    }

    public function index() {
      $this->hasAccessOrFail('list');

      $scanned = scandir(config_path());
      $configFiles = [];
      $cleanConfigs = [];

      unset($scanned[0]);
      unset($scanned[1]);

      foreach($scanned as $file) {
        if($this->endsWith($file, '.php')) {
          $configFiles[$file] = $file;
        } else {
          $subdir = scandir(config_path()."/{$file}");

          unset($subdir[0]);
          unset($subdir[1]);

          foreach($subdir as $subfile) {
            $configFiles[] = "{$file}.{$subfile}";
          }
        }
      }

      foreach($configFiles as $key => $file) {
        $fileName = str_replace(".php", "", $file);
        $cleanConfigs[$fileName] = str_replace(".php", "", $fileName);
      }

      foreach(config('cord.settings.files') as $file) {
        unset($cleanConfigs[$file]);
      }

      $cleanConfigs = array_values($cleanConfigs);

      $this->data['configFiles'] = $cleanConfigs;
      $this->data['title'] = $this->data['plural'];
      $this->data['canCreate'] = $this->hasAccess('create');
      $this->data['canUpdate'] = $this->hasAccess('update');

      $this->data['columns'] = [
        'key' => [
          'label' => 'Key',
        ],
        'value' => [
          'label' => 'Value',
        ],
        'actions' => [
          'label' => 'Actions',
        ],
      ];

      return view('cord::list', $this->data);
    }

    // search for all the options within that configuration file.
    public function search($file) {
      $options = [];

      $config = (array) new Repository($file); // loading the config from config/app.php


      foreach($config as $key => $value) {
        $newKey = str_replace("*", "", preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $key));

        $options[$newKey] = $value;
      }

      $options = $options['items'];

      foreach(config('cord.settings.ignore') as $optionFile => $ignoreOptions) {
        if($file == $optionFile) {
          foreach($ignoreOptions as $opt) {
            unset($options[$opt]);
          }
        }
      }

      $childArrays = $this->findSubArrays($options);

      // First layer of children
      foreach($childArrays as $child) {
        $options = $this->subOptions($options, $child);

        // second layer of children
        $childArrays = $this->findSubArrays($options);
        foreach($childArrays as $child) {
          $options = $this->subOptions($options, $child);
        }

        // second layer of children
        $childArrays = $this->findSubArrays($options);
        foreach($childArrays as $child) {
          $options = $this->subOptions($options, $child);
        }


      }

      return json_encode($options, JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    // make sure it ends with a specific file extension.
    private function endsWith($haystack, $needle) {
      $length = strlen($needle);

      return $length === 0 ||
      (substr($haystack, -$length) === $needle);
    }

    // find and create a list of all sub items that are an error
    private function findSubArrays($options) {
      $subArrays = [];
      foreach($options as $key => $option) {
        if(is_array($options[$key])) {
          $subArrays[] = $key;
        }
      }

      return $subArrays;
    }

    // Convert sub options that are an array to a string
    private function subOptions($options, $newKey) {
      if(isset($options[$newKey])) {
        foreach($options[$newKey] as $subKey => $value) {
          $options["{$newKey}.{$subKey}"] = $value;
        }

        unset($options[$newKey]);
      }

      return $options;
    }
}
