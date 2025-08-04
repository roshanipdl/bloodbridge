<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DonationRequestRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DonationRequestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DonationRequestCrudController extends CrudController
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
        CRUD::setModel(\App\Models\DonationRequest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/donation-request');
        CRUD::setEntityNameStrings('donation request', 'donation requests');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'blood_request_id',
            'label' => 'Recipient Name - Blood Group',
            'type' => 'select',
            'entity' => 'bloodRequest',
            'model' => \App\Models\BloodRequest::class,
            'attribute' => 'custom_label',
        ]);

        CRUD::column('donor_id');
        CRUD::column('status');
        CRUD::column('notes');

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
        CRUD::setValidation(DonationRequestRequest::class);

        CRUD::addField([
            'name' => 'blood_request_id',
            'type' => 'select',
            'entity' => 'bloodRequest',
            'model' => \App\Models\BloodRequest::class,
            'attribute' => 'custom_label',
            'label' => 'Blood Request'
        ]);
        CRUD::field('donor_id');
        CRUD::addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'options' => [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            ],
            'default' => 'pending',
            'label' => 'Status'
        ]);
        CRUD::field('notes');

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
