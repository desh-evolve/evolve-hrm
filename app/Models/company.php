<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class Company extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'com_companies';

    // Fields that are mass assignable
    protected $fillable = [
        'id',
        'company_name',
        'company_short_name',
        'industry_id',
        'business_reg_no',
        'address_1',
        'address_2',
        'city_id',
        'province_id',
        'country_id',
        'postal_code',
        'contact_1',
        'contact_2',
        'email',
        'epf_reg_no',
        'tin_no',
        'admin_contact_id',
        'billing_contact_id',
        'primary_contact_id',
        'logo',
        'logo_small',
        'website',
        'status',
        'created_date',
        'created_by',
        'updated_date',
        'updated_by',
        'deleted_date',
        'deleted_by',
    ];

    // Instance of CommonModel for reusable database operations
    private $common = null;

    // Constructor to initialize CommonModel
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    /**
     * Store a new company record
     *
     * @param array $data
     * @return mixed
     */
    public function storeRecord($data)
    {
        return $this->common->storeRecord('com_companies', $data);
    }

    /**
     * Update an existing company record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateRecord($id, $data)
    {
        return $this->common->updateRecord('com_companies', $id, $data);
    }

    /**
     * Get all company records
     *
     * @return mixed
     */
    public function getAllRecords()
    {
        return $this->common->getAllRecords('com_companies');
    }

    /**
     * Get a single company record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getSingleRecord($id)
    {
        return $this->common->getSingleRecord('com_companies', $id);
    }

    /**
     * Soft delete a company record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function destroyRecord($id, $data)
    {
        return $this->common->destroyRecord('com_companies', $id, $data);
    }
}
