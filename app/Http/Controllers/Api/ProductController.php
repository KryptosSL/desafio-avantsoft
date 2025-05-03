<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
        //
    }

    public function creteProduct(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ];

        $messages = [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser uma string.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'price.required' => 'O preço do produto é obrigatório.',
            'price.numeric' => 'O preço do produto deve ser um número.',
            'price.min' => 'O preço do produto deve ser maior ou igual a 0.',
            'quantity.required' => 'A quantidade em estoque do produto é obrigatória.',
            'quantity.integer' => 'A quantidade em estoque do produto deve ser um número inteiro.',
            'quantity.min' => 'A quantidade em estoque do produto deve ser maior ou igual a 0.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = $this->productService->save($request->all());

        return response()->json($product, 201);
    }

    public function updateProduct(Request $request, $id)
    {
 
        $idValidator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:products,id',
        ]);
    
        if ($idValidator->fails()) {
            return response()->json(['errors' => $idValidator->errors()], 422);
        }

        $rules = [
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $updated = $this->productService->update($id, $request->all());
    
        if (!$updated) {
            return response()->json(['error' => 'Erro ao atualizar o produto.'], 500);
        }
    
        return response()->json(['message' => 'Produto atualizado com sucesso.']);
    }

    public function deleteProduct(Request $request,$id)
    {
        
        $rules = [
            'id' => 'required|integer|exists:products,id',
        ];

        $validator = Validator::make(['id' => $id], $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->productService->delete($id);

        return response()->json(['message' => 'Produto excluído com sucesso.']);
    }

    public function findProduct(Request $request)
    {
        $rules = [
            'id' => 'required|integer|exists:products,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = $this->productService->find($request->input('id'));

        return response()->json($product);
    }

    public function listProducts(Request $request)
    {
        $products = $this->productService->listAll();

        return response()->json($products);
    }
}
