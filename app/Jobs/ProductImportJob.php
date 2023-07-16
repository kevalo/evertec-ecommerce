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

    private const HEADERS = [
        'nombre' => 0,
        'precio' => 1,
        'descripcion' => 2,
        'cantidad' => 3,
        'estado' => 4,
        'categoria' => 5,
        'id' => 6
    ];

    public function __construct(private readonly string $filePath, private readonly User $user)
    {
    }

    public function handle(): void
    {
        try {
            logger()->info("Proceso de importación de productos iniciado");
            $count = 0;

            $path = Storage::path($this->filePath);

            if (($file = fopen($path, 'rb')) !== false) {
                fgetcsv($file);

                while (($row = fgetcsv($file)) !== false) {
                    $this->processRow($row);
                    $count++;
                }

                fclose($file);
            }

            Storage::delete($this->filePath);

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
            default => throw new UnsupportedStatus(__('products.import_error'))
        };

        Product::query()->updateOrCreate([
            'id' => (
                array_key_exists(self::HEADERS['id'], $row) &&
                is_numeric(trim($row[self::HEADERS['id']]))
            ) ? $row[self::HEADERS['id']] : -1,
        ], [
            'name' => $row[self::HEADERS['nombre']],
            'slug' => Str::slug(trim($row[self::HEADERS['nombre']]), '-', 'es'),
            'price' => $row[self::HEADERS['precio']],
            'description' => $row[self::HEADERS['descripcion']],
            'quantity' => $quantity > 0 ? $quantity : 1,
            'status' => $status,
            'category_id' => $this->getCategoryId($row[self::HEADERS['categoria']])
        ]);
    }

    private function getCategoryId(string $categoryName): int
    {
        $category = Category::query()->firstOrCreate(
            ['name' => $categoryName],
            ['name' => $categoryName, 'status' => GeneralStatus::ACTIVE->value]
        );

        return $category->id;
    }
}
