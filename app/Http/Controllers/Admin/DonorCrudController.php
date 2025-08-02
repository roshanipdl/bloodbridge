<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DonorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DonorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DonorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Donor::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/donor');
        CRUD::setEntityNameStrings('donor', 'donors');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('blood_type');
        CRUD::column('total_donations');
        CRUD::column('contact');
        CRUD::column('latitude');
        CRUD::column('longitude');
        CRUD::column('is_available');
        CRUD::column('health_status');
        CRUD::column('last_donation_date');
        CRUD::column('user_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        CRUD::column('donation_history');
        CRUD::column('health_notes');
        CRUD::column('next_eligible_donation_date');
        CRUD::column('medical_conditions');
        CRUD::column('last_health_check_date');
        CRUD::column('donations_in_last_2_years');

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
        CRUD::setValidation(DonorRequest::class);

        CRUD::field('name');
        CRUD::addField([
            'name' => 'blood_type',
            'label' => 'Blood Type',
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
        CRUD::field('total_donations')->type('number');
        CRUD::field('contact');
        CRUD::field('latitude')->type('number');
        CRUD::field('longitude')->type('number');
        CRUD::addField([
            'name' => 'is_available',
            'label' => 'Is Available',
            'type' => 'boolean'
        ]);
        CRUD::addField([
            'name' => 'health_status',
            'label' => 'Health Status',
            'type' => 'select_from_array',
            'options' => [
                'good' => 'Good',
                'pending_review' => 'Pending Review',
                'not_eligible' => 'Not Eligible'
            ]
        ]);
        CRUD::field('last_donation_date');
        CRUD::field('user_id');
        CRUD::field('health_notes');
        CRUD::field('next_eligible_donation_date');
        CRUD::field('last_health_check_date');
        CRUD::field('donations_in_last_2_years')->type('number');

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
