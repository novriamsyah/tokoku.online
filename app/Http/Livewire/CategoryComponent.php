<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class CategoryComponent extends Component
{
    use WithPagination;
    public $sortingData;
    public $perPage;
    public $category_slug;

    public function mount($category_slug) {
        $this->sortingData = "default";
        $this->perPage = 12;
        $this->category_slug = $category_slug;
    }

    public function store($product_id,$product_name,$product_price){
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'Data Berhasil Ditambahkan ke Cart');
        return redirect()->route('product.cart');
    }
    
    public function render()
    {
        $category      = Category::where('slug',$this->category_slug)->first();
        $category_id   = $category->id;
        $category_name = $category->name;

        if ($this->sortingData == 'date') {

            $products = Product::where('category_id',$category_id )->orderBy('created_at', 'desc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price') {

            $products = Product::where('category_id',$category_id )->orderBy('regular_price', 'asc')->paginate($this->perPage);

        } else if ($this->sortingData == 'price-desc') {

            $products = Product::where('category_id',$category_id )->orderBy('regular_price', 'desc')->paginate($this->perPage);

        } else {

            $products = Product::where('category_id',$category_id )->paginate($this->perPage);

        }

        $categories = Category::all();

        return view('livewire.category-component', ['products'=>$products, 'categories'=>$categories,'category_name'=>$category_name])->layout("layouts.app1");
    }
}
