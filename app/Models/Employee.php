<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 * @package App\Models
 * @version 2023/10/09 0009, 16:38
 *
 * @property int id
 * @property string name
 * @property int age
 * @property string email
 * @property string country
 * @property int type
 * @property string job
 * @property string brief
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 */
class Employee extends Model
{
    use HasFactory;

    protected $table = "employees";

    protected $fillable = [
        "id",
        "name",
        "age",
        "email",
        "country",
        "type",
        "job",
        "brief",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return EmployeeFactory::new();
    }
}
