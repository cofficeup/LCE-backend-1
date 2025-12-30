<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceRepository
{
    /**
     * Persist draft invoice + lines atomically.
     */
    public function createDraft(array $invoiceData, array $lines): Invoice
    {
        return DB::transaction(function () use ($invoiceData, $lines) {

            $invoice = Invoice::create($invoiceData);

            foreach ($lines as $line) {
                $invoice->lines()->create($line);
            }

            return $invoice->load('lines');
        });
    }
}
