<?php

namespace Cord\Settings\app\Http\Controllers;

/**
 * Original Code From Laravel-Backpack/Settings
 * Minor Modifications By Abby Janke for CordPanel/Settings
 */

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Cord\Settings\app\Http\Requests\SettingRequest as StoreRequest;
use Cord\Settings\app\Http\Requests\SettingRequest as UpdateRequest;

class SettingCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Cord\Settings\app\Models\Setting');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/setting/app');
        $this->crud->setEntityNameStrings(trans('cord::settings.setting_singular'), trans('cord::settings.setting_plural'));
        $this->crud->denyAccess(['create', 'delete']);

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->setColumns([
          [
            'name'  => 'name',
            'label' => trans('cord::settings.name'),
          ],
          [
            'name'  => 'value',
            'label' => trans('cord::settings.value'),
          ],
          [
            'name'  => 'description',
            'label' => trans('cord::settings.description'),
          ],
        ]);

        $this->crud->addField([
          'name'       => 'name',
          'label'      => trans('cord::settings.name'),
          'type'       => 'text',
          'attributes' => [
            'disabled' => 'disabled',
          ],
        ]);
    }

    /**
     * Display all rows in the database for this entity.
     * This overwrites the default CrudController behaviour:
     * - instead of showing all entries, only show the "active" ones.
     *
     * @return Response
     */
    public function index()
    {
      $this->crud->addClause('where', 'active', 1);
      return parent::index();
    }

    public function store(StoreRequest $request)
    {
      return parent::storeCrud();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
      $this->crud->hasAccessOrFail('update');
      $entry = $this->crud->getEntry($id);
      $this->data['entry'] = $entry;
      $this->data['crud'] = $this->crud;
      $this->data['saveAction'] = $this->getSaveAction();
      $this->data['fields'] = $this->crud->getUpdateFields($id);
      $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
      $this->data['id'] = $id;
      // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
      return view($this->crud->getEditView(), $this->data);
    }

    public function update(UpdateRequest $request)
    {
      return parent::updateCrud();
    }
}
