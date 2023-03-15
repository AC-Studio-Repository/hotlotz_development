<?php

namespace App\Modules\Item\Models;

use App\Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemFeeStructure extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'item_fee_structures';

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public static function autoSaveSalesCommissionPayload($item_id)
    {
        $sales_commission['item_id'] = $item_id;
        $sales_commission['fee_type'] = 'sales_commission';
        $sales_commission['sales_commission'] = '20';
        $sales_commission['performance_commission_setting'] = null;
        $sales_commission['performance_commission'] = null;
        $sales_commission['minimum_commission_setting'] = 1;
        $sales_commission['minimum_commission'] = '40';
        $sales_commission['insurance_fee_setting'] = 1;
        $sales_commission['insurance_fee'] = '1.5';
        $sales_commission['listing_fee_setting'] = null;
        $sales_commission['listing_fee'] = null;
        $sales_commission['unsold_fee_setting'] = 0;
        $sales_commission['unsold_fee'] = '40';
        $sales_commission['withdrawal_fee_setting'] = 1;
        $sales_commission['withdrawal_fee'] = '60';

        ItemFeeStructure::create($sales_commission);
    }

    protected $appends = ['fee_total','fixed_cost_sales_fee_cal', 'sales_commission_cal', 'performance_commission_cal', 'insurance_fee_cal', 'listing_fee_cal'];

    public function getFeeTotalAttribute()
    {
        $price = 0;
        if($this->item->sold_price == null){
            return 0;
        }

        if(in_array($this->item->status ,[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {

            $price = $this->item->sold_price;

            if ($this->item->fee_type == 'fixed_cost_sales_fee') {
                $fee = str_replace(["$", "%", "+"], '', $this->fixed_cost_sales_fee);
                $price -= $fee;
            }

            if ($this->item->fee_type == 'sales_commission') {
                $commission = str_replace(["$", "%", "+"], '', $this->sales_commission);
                $fee = ($commission / 100) * $this->item->sold_price;

                $minimum_commission = (float) str_replace('$', '', $this->minimum_commission);

                if ($this->minimum_commission_setting == 1 && $fee <= $minimum_commission) {

                    $fee = $minimum_commission;
                }

                $price -= $fee;

                if ($this->performance_commission_setting == 1) {
                    if ($this->item->sold_price > $this->item->high_estimate) {
                        $commission = str_replace(["$", "%", "+"], '', $this->performance_commission);
                        $fee = ($commission / 100) * $this->item->sold_price;

                        $price -= $fee;

                    }
                }
            }

            if ($this->insurance_fee_setting == 1) {
                $commission = str_replace(["$", "%", "+"], '', $this->insurance_fee);
                $fee = ($commission / 100) * $this->item->sold_price;
                $price -= $fee;
            }

            if ($this->listing_fee_setting == 1) {
                $fee = str_replace(["$", "%", "+"], '', $this->listing_fee);
                $price -= $fee;
            }

        }

        return $price;
    }

    public function getFixedCostSalesFeeCalAttribute()
    {
        if (in_array($this->item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {
            if ($this->item->sold_price == null) {
                return 0;
            }

            if ($this->item->fee_type == 'fixed_cost_sales_fee') {
                $fee = str_replace(["$", "%", "+"], '', $this->fixed_cost_sales_fee);
                return number_format($fee, 2, '.', '');
            }
        }

        return 0;
    }

    public function getSalesCommissionCalAttribute()
    {
        if (in_array($this->item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {
            if ($this->item->sold_price == null) {
                return 0;
            }

            if ($this->item->fee_type == 'sales_commission') {
                $commission = str_replace(["$", "%", "+"], '', $this->sales_commission);
                $fee = ($commission / 100) * $this->item->sold_price;

                $minimum_commission = (float) str_replace('$', '', $this->minimum_commission);

                if ($this->minimum_commission_setting == 1 && $fee <= $minimum_commission) {
                    $fee = $minimum_commission;
                }

                return number_format($fee, 2, '.', '');
            }
        }
        return 0;
    }

    public function getPerformanceCommissionCalAttribute()
    {
        if (in_array($this->item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {
            if ($this->item->sold_price == null) {
                return 0;
            }

            if ($this->performance_commission_setting == 1) {
                if ($this->item->sold_price > $this->item->high_estimate) {
                    $commission = str_replace(["$", "%", "+"], '', $this->performance_commission);
                    $fee = ($commission / 100) * $this->item->sold_price;
                    return number_format($fee, 2, '.', '');
                }
            }
        }
        return 0;
    }

    public function getInsuranceFeeCalAttribute()
    {
        if (in_array($this->item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {
            if ($this->item->sold_price == null) {
                return 0;
            }

            if ($this->insurance_fee_setting == 1) {
                $commission = str_replace(["$", "%", "+"], '', $this->insurance_fee);
                $fee = ($commission / 100) * $this->item->sold_price;
                return number_format($fee, 2, '.', '');
            }
        }
        return 0;
    }

    public function getListingFeeCalAttribute()
    {
        if (in_array($this->item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($this->item->status == Item::_DISPATCHED_ || $this->item->tag == 'dispatched')) {
            if ($this->item->sold_price == null) {
                return 0;
            }

            if ($this->listing_fee_setting == 1) {
                $fee = str_replace(["$", "%", "+"], '', $this->listing_fee);
                return number_format($fee, 2, '.', '');
            }
        }
        return 0;
    }

}
