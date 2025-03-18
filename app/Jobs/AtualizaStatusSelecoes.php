<?php

namespace App\Jobs;

use App\Models\Selecao;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AtualizaStatusSelecoes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Selecao::where('created_at', '>=', Carbon::today()->subYears(2))->get() as $selecao)
            $selecao->atualizarStatus();
    }
}
