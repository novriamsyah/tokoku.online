<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class SearchComponent extends Component
{
    use WithPagination;
    public $sortingData;
    public $perPage;

    public $search;
    public $product_cat;
    public $product_cat_id;

    public function mount() {
        $this->sortingData = "default";
        $this->perPage = 12;
        $this->fill(request()->only('search','product_cat','product_cat_id'));
    }

    public function store($product_id,$product_name,$product_price){
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'Data Berhasil Ditambahkan ke Cart');
        return redirect()->route('product.cart');
    }
    
    public function render()
    {
        if ($this->sortingData == 'date') {

            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('created_at', 'desc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price') {

            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price', 'asc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price-desc') {

            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price', 'desc')->paginate($this->perPage);

        } else {

            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->paginate($this->perPage);

        }

        $categories = Category::all();

        return view('livewire.search-component', ['products'=>$products, 'categories'=>$categories])->layout("layouts.app1");
    }
}
