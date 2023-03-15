<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\ProfessionalValuations\Models\ProfessionalValuations;

class ProfessionalValuationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('professional_valuations')->truncate();
        $rows = [
            [
                'value' => "<h3 class=\"ws-black font_36\">Selling At Hotlotz</h3>
                <div class=\"divider pt-2\"></div>
                <p class=\"pt-3\">
                    Taking part in an auction at Tennants has never been easier, whether you want to bid in person in the saleroom or browse lots and bid on the go. <br><br>
                    We want to make buying at auction as transparent, accessible and straightforward as possible, no matter the price point of the lot you are after.
                </p>
    
                <h4 class=\"pt-bold font_28 pt-3\">Buyer's Fees Explained</h4>
                <div class=\"divider pt-2\"></div>
                <p class=\"pt-3\">
                The following rates of buyer’s premium will be added to the hammer price of each lot that you purchase. <br><br>
                &nbsp;&nbsp;&nbsp;• Art Sales, Country House Sales, Specialist Sales and Automobilia Sales: 20% <br>
                &nbsp;&nbsp;&nbsp;• Antiques and Interiors Sales: 17.5% <br>
                &nbsp;&nbsp;&nbsp;• Motor Vehicles: 10% <br><br>
                VAT at the current rate of 20% will be added to the buyer’s premium of all lots (with the exception of books and unframed maps), and any extra charges such as transport. <br><br>
                VAT may be applied to the hammer price of certain lots. These will be clearly marked in the online and printed catalogue.
                </p>
    
                <h4 class=\"pt-bold font_28 pt-3\">Find An Item</h4>
                <div class=\"divider pt-2\"></div>
    
                
                <h5 class=\"pt-bold font_20 pt-3\">Online</h5>
                <p class=\"pt-2\">All our auctions are listed in our auction calendar, along with forthcoming sale highlights and all the latest auction news. Fully illustrated catalogues are available in the days leading up to the sale, and you can subscribe to receive email alerts when catalogues go live online.</p>
    
                <h5 class=\"pt-bold font_20 pt-3\">In Person</h5>
                <p class=\"pt-2\">All our sales are open for public viewing in our Leyburn salerooms. Viewing times vary for each sale, so please check sale listings or the online catalogue for each sale.</p>
    
                <h5 class=\"pt-bold font_20 pt-3\">Catalogues</h5>
                <p class=\"pt-2\">Printed catalogues can be purchased in our Leyburn salerooms or in our Harrogate office. Alternatively, you can subscribe to receive catalogues for the Art Sales, Modern and Contemporary Sales, and 20th Century Design Sales by post. Please contact Gussie Wood on +44 (0)1969 623780. Annual subscription - UK £55 (inc. p&P) Single catalogues – UK £20 (inc. p&p) Please enquire for catalogue prices for Europe and the rest of the world.</p>"
            ]
        ];

        if (count($rows) > 0) {
            Seed::insertData(ProfessionalValuations::class, $rows);
        }
    }
}
