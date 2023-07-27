<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Mockery\Exception;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $orders =  Order::groupBy('id')
                    ->selectRaw('id, sum(subtotal) as total,
                    customer_name, status, created_at')
                ->paginate(50);

        return Inertia::render('Purchases/index',
            ['orders' => $orders]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        //$customers = Customer::select('id', 'name', 'kana')->get();
        $items = Item::select('id', 'name', 'price')->where('is_selling', true)->get();

        return Inertia::render('Purchases/create',
            [
                //'customers' => $customers,
                'items' => $items]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'customer_id' => $request->post("customer_id"),
                'status' =>  $request->post("status")
            ]);

            foreach ($request->post("items") as $item) {
                $purchase->items()->attach($purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);

            }
            DB::commit();

        } catch(Exception $e) {
            DB::rollBack();
        }
        return to_route("dashboard");
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Inertia\Response
     */
    public function show(Purchase $purchase)
    {
        //小計
        $items = Order::where('id', $purchase->id)->get();
        //合計
        $order =  Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, sum(subtotal) as total,
                    customer_name, status, created_at')
            ->get();

        return Inertia::render('Purchases/show', [
            "items" => $items,
            "order" => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Inertia\Response
     */
    public function edit(Purchase $purchase)
    {
        $p = Purchase::find($purchase->id);
        $allitems = Item::select('id', 'name', 'price')
        ->get();
        $items = [];
        foreach ($allitems as $allitem) {
            $quantity = 0;
            foreach ($p->items as $item) {
                if ($allitem->id === $item->id)  {
                    $quantity = $item->pivot->quantity;

                }
            }
            array_push($items, [
                'id' => $allitem->id,
                'name' => $allitem->name,
                'price' => $allitem->price,
                'quantity' => $quantity,
            ]);



        }
        $order =  Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_id,
                    customer_name, status, created_at')
            ->get();

        return Inertia::render('Purchases/edit', [
            'items' => $items,
            'order' => $order,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase->status = $request->post("status");
            $purchase->save();

            $items = [];
            foreach ($request->post("items") as $item) {

                $items[$item['id']] = [
                    'quantity' => $item['quantity'],
                ];
            }
            $purchase->items()->sync($items);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }

        return to_route("dashboard");



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
