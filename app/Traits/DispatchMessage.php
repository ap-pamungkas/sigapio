<?php

namespace App\Traits;

use Livewire\Features\SupportJsEvaluation\HandlesJsEvaluation;

trait DispatchMessage
{
     use HandlesJsEvaluation;

    public function success($message, int $timer = 1500){
        $this->js('

        Swal.fire({

            icon: "success",
            title: "Success",
            text: "' . $message . '",
            showConfirmButton: false,
            timer: ' . $timer . '

        });
    ');
    }

    public function error($message, int $timer = 1500 ){
        $this->js('

            Swal.fire({

                icon: "error",
                title: "Error",
                text: "' . $message . '",
                showConfirmButton: false,
                timer:' .$timer. '
            });
        ');
    }
}
