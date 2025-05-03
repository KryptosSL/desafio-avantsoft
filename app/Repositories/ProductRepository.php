<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    
    public function find(int $id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = Product::find($id);
        return $product ? $product->update($data) : false;
    }

    public function delete(int $id)
    {
        return Product::destroy($id);
    }

    public function all()
    {
        return Product::all();
    }
}
