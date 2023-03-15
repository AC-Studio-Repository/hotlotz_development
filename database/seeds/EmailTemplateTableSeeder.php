<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\EmailTemplate\Models\EmailTemplate;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->truncate();
        
        $rows = [
            [
                'id' => 1,
                'title' => 'Request Received Email',
                'type' => 'item',
                'description' => 'to be sent out when the customer filled in "sell with us" form',
                'content' => '',
            ],
            [
                'id' => 2,
                'title' => 'Item Received Email',
                'type' => 'item',
                'description' => 'to be sent out when HL received the physical item',
                'content' => '',
            ],
            [
                'id' => 3,
                'title' => 'Consignment Agreement Email',
                'type' => 'item',
                'description' => 'to be sent out after valuation and cataloguing are done and lifecycle & fee packages are verbally agreed by the customer. The email will contain the link where the customer accesses the list of items via a webpage and click to agree to terms & conditions for each item.',
                'content' => '',
            ],
            [
                'id' => 4,
                'title' => 'Consignment Contract Email',
                'type' => 'item',
                'description' => 'after sign off',
                'content' => '',
            ],
            [
                'id' => 5,
                'title' => 'Pre-sale Advice Email',
                'type' => 'auction',
                'description' => '',
                'content' => '',
            ],
            [
                'id' => 6,
                'title' => 'Post-sale Advice Email',
                'type' => 'auction',
                'description' => '',
                'content' => '',
            ],
            [
                'id' => 7,
                'title' => 'Auction Invoice Draft Email',
                'type' => 'auction',
                'description' => 'send email to Winners with link to choose a delivery method, payment method, etc',
                'content' => '',
            ],
            [
                'id' => 8,
                'title' => 'Auction Invoice Email',
                'type' => 'auction',
                'description' => 'send email to Winners with link to payment gateway to pay',
                'content' => '',
            ],
            [
                'id' => 9,
                'title' => 'Settlement Email',
                'type' => '', //auction or marketplace
                'description' => 'weekly to Seller',
                'content' => '',
            ],
            [
                'id' => 10,
                'title' => 'Marketplace Receipt Email',
                'type' => '',
                'description' => 'For example; Your order is confirmed etc..',
                'content' => '',
            ],
            [
                'id' => 11,
                'title' => 'Hotlotz - Your Confirmation Required',
                'type' => '',
                'description' => "Creation of your Hotlotz account",
                'content' => "<p>Creation of your Hotlotz account
                </p><p> 
                Thank you for creating a Hotlotz account. Simply click on the link below to confirm your email address and your account will be activated.
                </p>
                <p>LINK</p>
                <p></p>
                <p>                
                Should the link not work for you, you can copy and paste it into your browser address bar.
                </p>
                <p>Once you have activated your account, you will have full access to our online features:
                </p><p>                 
                -       Register and bid in timed auctions
                </p><p>-       Instantly purchase items in the Hotlotz marketplace
                </p><p>-       Request appraisals and valuations
                </p><p>-       View your invoices and pay online
                </p><p>-       Record your interests for personalised recommendations
                </p><p><br></p><p>                 
                If you have any questions, please don’t hesitate to contact us.</p>",
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(EmailTemplate::class, $rows);
        }
    }
}