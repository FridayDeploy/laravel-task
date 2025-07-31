<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $patient_id
 * @property string $name
 * @property string $value
 * @property string $reference
 */
class Order extends Model
{
    use HasFactory;
}
