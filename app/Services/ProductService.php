<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
       $this->productRepository = $productRepository;
    }

    public function save(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function get(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function listAll()
    {
        return $this->productRepository->all();
    }
}
