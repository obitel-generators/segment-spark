<?php

namespace Keithbrink\SegmentSpark\Observers;

use Laravel\Spark\LocalInvoice;
use Segment;

class LocalInvoiceObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(LocalInvoice $invoice)
    {
        Segment::track(array(
            "userId" => $invoice->user->id,
            "event" => "Order Completed",

            "properties" => array(
                "products" => array(array(
                    "product_id" => $invoice->user->sparkPlan()->id,
                    "sku" => $invoice->user->sparkPlan()->id,
                    "name" => $invoice->user->sparkPlan()->name,
                    "price" => $invoice->user->sparkPlan()->price,
                    "quantity" => 1,
                )),
                "order_id" => $invoice->id,
                "total" => $invoice->total,
                "tax" => $invoice->tax,
                "discount" => $invoice->total - $invoice->tax - $invoice->user->sparkPlan()->price,
            )
        )); 
        Segment::flush();
    }
}