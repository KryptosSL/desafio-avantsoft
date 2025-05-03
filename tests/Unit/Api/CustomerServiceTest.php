<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CustomerService;
use App\Repositories\CustomerRepository;
use Mockery;

class CustomerServiceTest extends TestCase
{
    protected $customerRepository;
    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->customerRepository = Mockery::mock(CustomerRepository::class);
        $this->customerService = new CustomerService($this->customerRepository);
    }

    public function testSaveCustomer()
    {
        $data = [
            'email' => 'cliente@example.com',
            'cpf' => '12345678900',
            'password' => 'senha123'
        ];

        $this->customerRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object) $data);


        $result = $this->customerService->saveCustomer($data);
        $this->assertEquals('cliente@example.com', $result->email);
        $this->assertEquals('12345678900', $result->cpf);
    }
}
