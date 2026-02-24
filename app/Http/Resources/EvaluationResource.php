<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->nama_penilaian,
            'kategori' => $this->jenis,
            'tanggal' => $this->tanggal,
            'rincian_nilai' => $this->whenLoaded('details', function () {
                return $this->details->map(fn($d) => [
                    'nama_siswa' => $d->student->name,
                    'skor' => $d->nilai
                ]);
            }),
        ];
    }
}
