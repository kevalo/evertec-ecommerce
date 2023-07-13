<?php

namespace App\Jobs;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Mail\ImportMail;
use App\Support\Definitions\GeneralStatus;
use App\Support\Exceptions\UnsupportedStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private int $categoryId;

    private const HEADERS = [
        'nombre' => 0,
        'precio' => 1,
        'descripcion' => 2,
        'cantidad' => 3,
        'estado' => 4,
    ];

    public function __construct(private readonly string $filePath, private readonly User $user)
    {
        $this->categoryId = $this->getGeneralCategoryId();
    }

    public function handle(): void
    {
        try {
            logger()->info("Proceso de importación de productos iniciado");
            $count = 0;

            if (($file = fopen(Storage::path($this->filePath), 'rb')) !== false) {
                fgetcsv($file);

                while (($row = fgetcsv($file)) !== false) {
                    $this->processRow($row);
                    $count++;
                }

                fclose($file);
            }

            logger()->info("Proceso de importación de productos finalizado, se encontraron " . $count . " registros.");
            Mail::to($this->user)
                ->send((new ImportMail(__('products.import_successfully')))->subject(__('products.import')));
        } catch (\Exception $exception) {
            logger()->warning('Error al importar productos', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ]);

            Mail::to($this->user)->send((new ImportMail(__('products.import_error')))->subject(__('products.import')));
        }
    }

    /**
     * @throws UnsupportedStatus
     */
    private function processRow(array $row): void
    {
        $quantity = (int)$row[self::HEADERS['cantidad']];
        $status = match (strtolower(trim($row[self::HEADERS['estado']]))) {
            'activo' => GeneralStatus::ACTIVE->value,
            'inactivo' => GeneralStatus::INACTIVE->value,
            default => throw new UnsupportedStatus(__('categories.error_status_update'))
        };

        Product::query()->updateOrCreate([
            'slug' => Str::slug($row[self::HEADERS['nombre']], '-', 'es'),
        ], [
            'name' => $row[self::HEADERS['nombre']],
            'price' => $row[self::HEADERS['precio']],
            'description' => $row[self::HEADERS['descripcion']],
            'image' => '',
            'quantity' => $quantity > 0 ? $quantity : 1,
            'status' => $status,
            'category_id' => $this->categoryId
        ]);
    }

    private function getGeneralCategoryId(): int
    {
        return Category::query()->firstOrCreate(['name' => 'General'], ['name' => 'General'])->id;
    }
}
