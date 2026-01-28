<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_type',
        'subtotal',
        'discount_amount',
        'total_amount',
        'payment_type',
        'payment_status',
        'notes',
        'due_amount',
        'user_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Generate unique invoice numbers
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';

        // Get the latest invoice with new format (INV-0001, INV-0002, etc.)
        // Only match 4-digit numbers after INV-
        $lastInvoice = self::where('invoice_number', 'REGEXP', '^INV-[0-9]{4}$')
            ->orderByRaw('CAST(SUBSTRING(invoice_number, 5) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            $lastNumber = intval(end($parts));
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
