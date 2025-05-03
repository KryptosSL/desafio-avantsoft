<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    
    public function __construct(private CustomerService $customerService)
    {
      
    }

    public function createCustomer(Request $request)
    {    
        $rules = [
            'email' => 'required|email|unique:customers,email',
            'cpf' => 'required|unique:customers,cpf',
            'password' => 'required|string|min:8',
        ];
    
        $messages = [
            'email.required' => 'O email do cliente é obrigatório.',
            'email.email' => 'O email do cliente deve ser válido.',
            'email.unique' => 'Já existe um cliente cadastrado com este email.',
            'cpf.required' => 'O cpf do cliente é obrigatório.',
            'cpf.unique' => 'Já existe um cliente cadastrado com este cpf.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.' 
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $customer = $this->customerService->saveCustomer($data);
    
        return response()->json($customer, 201);
    }
 
    public function getCustomer(Request $request, $id)
    {
        $rules = [
            'id' => 'required|integer|exists:customers,id',
        ];
    
        $messages = [
            'id.required' => 'O ID do cliente é obrigatório.',
            'id.integer' => 'O ID do cliente deve ser um número inteiro.',
            'id.exists' => 'O cliente com o ID fornecido não existe.',
        ];
    
        $validator = Validator::make(['id' => $id], $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $customer = $this->customerService->getCustomer($id);
    
        if (!$customer) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }
    
        return response()->json($customer, 200);
    }
   
    public function updateCustomer(Request $request, $id)
    {
        $rules = [
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'cpf' => 'nullable|unique:customers,cpf,' . $id,
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20'
        ];

        $messages = [
            'email.required' => 'O email do cliente é obrigatório.',
            'email.email' => 'O email do cliente deve ser válido.',
            'email.unique' => 'Já existe um cliente cadastrado com este email.',
            'cpf.required' => 'O cpf do cliente é obrigatório.',
            'cpf.unique' => 'Já existe um cliente cadastrado com este cpf.',
            'name.string' => 'O nome deve ser uma string válida.',
            'phone.string' => 'O telefone deve ser uma string válida.', 
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = $this->customerService->updateCustomer($id, $request->all());

        if (!$customer) {
            return response()->json(['message' => 'Cliente não encontrado.'], 404);
        }

        return response()->json($customer, 200);
    }

  
    public function deleteCustomer($id)
    {
       
        $this->customerService->deleteCustomer($id);
        return response()->json(null, 204); 
    }

   
    public function listCustomers(Request $request)
    {
 
        $filters = $request->only(['name', 'email', 'cpf']);
        $customers = $this->customerService->listCustomers($filters);
        return response()->json($customers, 200);
    }
}