<?php

namespace  App\Modules\Xero\Http\Controllers;

use Webfox\Xero\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Modules\Xero\Events\XeroWebhookEvent;
use App\Modules\Xero\Events\InvoiceWasUpdated;

class XeroWebhookController extends Controller
{
    public function __invoke(Request $request, Webhook $webhook)
    {
        if (!$webhook->validate($request->header('x-xero-signature'))) {
            return response('', Response::HTTP_UNAUTHORIZED);
        }
        \Log::channel('xeroLog')->info("Xero Event Count : " . sizeof($webhook->getEvents()));
        \Log::channel('xeroLog')->info("Xero Event Get Signature : " . $webhook->getSignature());
        \Log::channel('xeroLog')->info("Xero Event Get First Event Sequence : " . $webhook->getFirstEventSequence());
        \Log::channel('xeroLog')->info("Xero Event Get Last Event Sequence : " . $webhook->getLastEventSequence());
        event(new XeroWebhookEvent($webhook->getProperties()));
        return response('', Response::HTTP_OK);
    }
}
