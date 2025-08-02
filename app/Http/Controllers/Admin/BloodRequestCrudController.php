<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BloodRequestRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BloodRequestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BloodRequestCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\BloodRequest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/blood-request');
        CRUD::setEntityNameStrings('blood request', 'blood requests');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('recipient_name');
        CRUD::column('blood_group');
        CRUD::column('units_required');
        CRUD::column('urgency_level');
        CRUD::column('additional_info');
        CRUD::column('notes');
        CRUD::column('status');
        CRUD::column('recipient_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        CRUD::column('latitude');
        CRUD::column('longitude');
        CRUD::column('required_by_date');
        CRUD::column('donor_id');
        CRUD::column('fulfill_date');
        CRUD::column('created_by');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BloodRequestRequest::class);

        CRUD::field('recipient_name');
        CRUD::addField([
            'name' => 'blood_group',
            'label' => 'Blood Group',
            'type' => 'select_from_array',
            'options' => [
                'A+' => 'A+',
                'A-' => 'A-',
                'B+' => 'B+',
                'B-' => 'B-',
                'AB+' => 'AB+',
                'AB-' => 'AB-',
                'O+' => 'O+',
                'O-' => 'O-'
            ]
        ]);

        CRUD::addField([
            'name' => 'urgency_level',
            'label' => 'Urgency Level',
            'type' => 'select_from_array',
            'options' => [
                'normal' => 'Normal',
                'urgent' => 'Urgent',
                'critical' => 'Critical'
            ]
        ]);

        CRUD::addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Pending',
                'fulfilled' => 'Fulfilled',
                'cancelled' => 'Cancelled'
            ]
        ]);

        CRUD::field('units_required');
        CRUD::field('additional_info');
        CRUD::field('notes');
        CRUD::field('recipient_id');
        CRUD::field('latitude')->type('number');
        CRUD::field('longitude')->type('number');
        CRUD::field('required_by_date');
        CRUD::field('donor_id');
        CRUD::field('fulfill_date');
        CRUD::field('creator');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
