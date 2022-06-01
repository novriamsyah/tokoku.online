<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class ShopComponent extends Component
{
    use WithPagination;
    public $sortingData;
    public $perPage;

    public function mount() {
        $this->sortingData = "default";
        $this->perPage = 12;
    }

    public function store($product_id,$product_name,$product_price){
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'Data Berhasil Ditambahkan ke Cart');
        return redirect()->route('product.cart');
    }
    
    public function render()
    {
        if ($this->sortingData == 'date') {

            $products = Product::orderBy('created_at', 'desc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price') {

            $products = Product::orderBy('regular_price', 'asc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price-desc') {

            $products = Product::orderBy('regular_price', 'desc')->paginate($this->perPage);

        } else {

            $products = Product::paginate($this->perPage);

        }

        $categories = Category::all();

        return view('livewire.shop-component', ['products'=>$products, 'categories'=>$categories])->layout("layouts.app1");
    }
}
