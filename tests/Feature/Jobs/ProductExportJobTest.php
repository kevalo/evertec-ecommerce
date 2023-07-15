<?php

namespace Tests\Feature\Jobs;

use App\Domain\Users\Models\User;
use App\Jobs\ProductExportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductExportJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_export_file_ok(): void
    {
        Storage::fake("public");
        (new ProductExportJob(User::factory()->admin()->create()))->handle();
        Storage::disk('public')->assertExists('exports/export_productos.csv');
    }
}
