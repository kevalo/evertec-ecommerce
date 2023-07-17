<?php

namespace App\Jobs;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Mail\ExportMail;
use App\Support\Definitions\GeneralStatus;
use App\Support\Exceptions\UnsupportedStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public function __construct(private readonly User $user)
    {
    }

    public function handle(): void
    {
        logger()->info("Proceso de exportación de productos iniciado");

        $headers = [
            'nombre',
            'precio',
            'descripción',
            'cantidad',
            'estado',
            'categoría',
            'id'
        ];

        $fileName = "export_productos.csv";
        $this->createFile($fileName);
        $file = $this->openFile($fileName);

        fwrite($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputcsv($file, $headers);

        Product::with('category')->chunk(5, function ($products) use ($file, $headers) {
            /** @var Product $product */
            foreach ($products as $product) {
                $status = match ($product->status) {
                    GeneralStatus::ACTIVE => 'activo',
                    GeneralStatus::INACTIVE => 'inactivo',
                    default => throw new UnsupportedStatus(__('products.import_error'))
                };

                fputcsv($file, [
                    $headers[0] => $product->name,
                    $headers[1] => $product->price,
                    $headers[2] => $product->description,
                    $headers[3] => $product->quantity,
                    $headers[4] => $status,
                    $headers[5] => $product->category->name,
                    $headers[6] => $product->id
                ]);
            }
        });

        fclose($file);

        Mail::to($this->user)->send(
            (new ExportMail(
                __('products.export_success'),
                url(Storage::disk('public')->url("exports/" . $fileName))
            ))->subject(__('products.export'))
        );

        logger()->info("Proceso de exportación de productos finalizado");
    }

    private function createFile(string $fileName): void
    {
        Storage::disk('public')->put("exports/" . $fileName, '');
    }

    private function openFile(string $fileName)
    {
        return fopen(Storage::disk('public')->path("exports/" . $fileName), 'wb');
    }
}
