<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminOrderDetailsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.database' => 'dalbit_test']);
    }

    public function test_guest_is_redirected_from_admin_order_details(): void
    {
        $order = Order::create([
            'customer_name' => 'Test Customer',
            'phone' => '0791234567',
            'location' => 'Amman Downtown',
            'items' => [
                ['name' => 'Caramel Bite', 'quantity' => 2, 'price' => 5.00],
            ],
            'total_amount' => 10.00,
            'status' => OrderStatus::PendingConfirmation,
            'ip_address' => '127.0.0.1',
            'delivery_date' => Carbon::tomorrow('Asia/Amman')->format('Y-m-d'),
            'delivery_slot' => '9-12',
        ]);

        $response = $this->get(route('admin.orders.show', $order));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_view_order_details(): void
    {
        $admin = Admin::create([
            'name' => 'Order Admin',
            'email' => 'admin.details@dalbit.app',
            'password' => bcrypt('password12345'),
        ]);

        $order = Order::create([
            'customer_name' => 'Special Customer',
            'phone' => '0789876543',
            'location' => 'Abdoun, Amman',
            'gift_note' => 'Happy Birthday!',
            'items' => [
                ['name' => 'Hazelnut Eye Bite', 'quantity' => 3, 'price' => 6.50],
            ],
            'total_amount' => 19.50,
            'status' => OrderStatus::New,
            'ip_address' => '127.0.0.1',
            'delivery_date' => Carbon::tomorrow('Asia/Amman')->format('Y-m-d'),
            'delivery_slot' => '12-15',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Special Customer');
        $response->assertSee('0789876543');
        $response->assertSee('Abdoun, Amman');
        $response->assertSee('Happy Birthday!');
        $response->assertSee('Hazelnut Eye Bite');
        $response->assertSee('19.50');
    }

    public function test_admin_can_update_order_status_from_details_page(): void
    {
        $admin = Admin::create([
            'name' => 'Status Admin',
            'email' => 'admin.status@dalbit.app',
            'password' => bcrypt('password12345'),
        ]);

        $order = Order::create([
            'customer_name' => 'Status Change Customer',
            'phone' => '0771112223',
            'location' => 'Jabal Amman',
            'items' => [
                ['name' => 'Chocolate Bite', 'quantity' => 1, 'price' => 4.00],
            ],
            'total_amount' => 4.00,
            'status' => OrderStatus::PendingConfirmation,
            'ip_address' => '127.0.0.1',
            'delivery_date' => Carbon::tomorrow('Asia/Amman')->format('Y-m-d'),
            'delivery_slot' => '15-18',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.orders.status', $order), [
                'status' => 'in_progress',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Order status updated.');

        $order->refresh();
        $this->assertEquals(OrderStatus::InProgress, $order->status);
        $this->assertEquals($admin->id, $order->handled_by_admin_id);
        $this->assertNotNull($order->handled_at);
    }

    public function test_invalid_order_id_returns_404(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin.404@dalbit.app',
            'password' => bcrypt('password12345'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get('/admin/orders/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(404);
    }
}
